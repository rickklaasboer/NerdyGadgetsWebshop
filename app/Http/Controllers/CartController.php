<?php

namespace App\Http\Controllers;

use App\Entities\StockItem;
use App\Util\Cart;
use App\Util\Request;

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
        ]);
    }

    /**
     * Add something to cart
     */
    public function add(Request $request)
    {
        ['product' => $product, 'amount' => $amount] = $request->only('product', 'amount');

        if (!$this->cart->hasAmountInStock($product, abs($amount ?? 1))) {
            return response()->flash('errors', [__('messages.cart.no_stock')])->redirect(url()->prev());
        }

        $this->cart->add(
            $product,
            abs($amount ?? 1)
        );

        return response()->redirect(url()->prev());
    }

    /**
     * Modify something in cart
     */
    public function modify(Request $request)
    {
        ['product' => $product, 'amount' => $amount] = $request->only('product', 'amount');

        if (!$this->cart->hasAmountInStock($product, abs($amount ?? 1))) {
            return response()->flash('errors', [__('messages.cart.no_stock', ['amount' => $amount])])->redirect(url()->prev());
        }

        $this->cart->modify(
            $product,
            abs($amount ?? 1),
            Cart::MODIFY_MODE_SET
        );

        return response()->redirect(url()->prev());
    }

    /**
     * Remove something from cart
     */
    public function remove(Request $request)
    {
        $this->cart->remove(
            $request->product
        );

        return response()->redirect(url()->prev());
    }

    /**
     * Api response for shopping cart menu
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function api()
    {

        if ($this->cart->isEmpty()) {
            return response()->json([
                'items' => null,
                'total_price' => 0,
            ]);
        }

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