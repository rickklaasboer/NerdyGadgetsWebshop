<?php

namespace App\Http\Controllers;

use App\Entities\StockItem;
use App\Util\Cart;

class CartController extends Controller
{
    /**
     * Show cart
     */
    public function show()
    {
        if ($this->cart->hasItems()) {
            $q = $this->manager->createQueryBuilder();
            $expr = $this->manager->getExpressionBuilder();

            $q->select('s')
                ->from('App\Entities\StockItem', 's')
                ->where(
                    $expr->in('s.StockItemID', $this->cart->getIds())
                );

            $stockItems = $q->getQuery()->getResult();
        }

        return response()->twig('cart.twig', [
            'cart' => $this->cart,
            'stockItems' => $stockItems ?? [],
            'errors' => $this->request->getSession()->getFlashBag()->get('errors')[0] ?? null,
        ]);
    }

    /**
     * Add something to cart
     */
    public function add()
    {
        $this->cart->add(
            $this->request->get('product'),
            abs($this->request->get('amount', 1))
        );

        return response()->redirect(url()->prev());
    }

    /**
     * Modify something in cart
     */
    public function modify()
    {
        $this->cart->modify(
            $this->request->get('product'),
            abs($this->request->get('amount', 1)),
            Cart::MODIFY_MODE_SET
        );

        return response()->redirect(url()->prev());
    }

    /**
     * Remove something from cart
     */
    public function remove()
    {
        $this->cart->remove(
            $this->request->get('product')
        );

        return response()->redirect(url()->prev());
    }

    public function api()
    {
        $stockItems = $this->manager->createQueryBuilder()
            ->select('s')
            ->from(StockItem::class, 's')
            ->where(
                $this->manager->getExpressionBuilder()
                    ->in('s.StockItemID', $this->cart->getIds())
            )->getQuery()->getResult();

        $cart = $this->cart->all();

        foreach ($stockItems as $product) {
            $cart[$product->getStockItemID()] = [
                'cart' => $cart[$product->getStockItemID()],
                'meta' => $product,
            ];
        }

        return response()->json([
            'items' => array_values($cart),
            'total_price' => $this->cart->price($stockItems)['price_total']
        ]);
    }
}