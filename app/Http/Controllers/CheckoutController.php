<?php

namespace App\Http\Controllers;

use App\Entities\StockItem;
use App\Exceptions\Http\HttpForbiddenException;
use App\Util\Cart;
use App\Util\Validation\Rules\Max;
use App\Util\Validation\Rules\Min;
use App\Util\Validation\Rules\Required;
use App\Util\Validation\Validator;
use Doctrine\ORM\EntityManager;
use Mollie\Api\MollieApiClient;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class CheckoutController extends Controller
{
    public function __construct(Environment $twig, Cart $cart, EntityManager $manager, Request $request)
    {
        parent::__construct($twig, $cart, $manager, $request);

        if (!auth()->isLoggedIn()) {
            throw new HttpForbiddenException();
        }
    }

    /**
     * Checkout overview
     *
     * @param Request $request
     * @param EntityManager $em
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function overview(Request $request, EntityManager $em, Cart $cart)
    {
        $stockItems = $em->createQueryBuilder()
            ->select('s')
            ->from(StockItem::class, 's')
            ->where($em->getExpressionBuilder()->in('s.StockItemID', $cart->getIds()))
            ->getQuery()
            ->getResult();

        return response()->twig('checkout/overview.twig', [
            'stockItems' => $stockItems,
            'cart' => $cart,
        ]);
    }

    /**
     * Get ready to pay an order
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function pay(Request $request, Cart $cart, MollieApiClient $mollie)
    {
        $form = \App\Util\Request::from($request)
            ->only('first_name', 'last_name', 'street_name', 'house_number', 'city', 'postal_code');

        $validator = new Validator($form, [
            'first_name' => [new Required(), new Min(1), new Max(255)],
            'last_name' => [new Required(), new Min(1), new Max(255)],
            'street_name' => [new Required(), new Min(1), new Max(255)],
            'house_number' => [new Required(), new Min(1), new Max(255)],
            'city' => [new Required(), new Min(1), new Max(255)],
            'postal_code' => [new Required(), new Min(1), new Max(255)]
        ]);

        if ($validator->fails()) {
            return response()->flash('errors', $validator->messages())
                ->redirect(url()->prev());
        }

        $payment = $mollie->payments->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => '1.00'
            ],
            "description" => "My first API payment",
            "redirectUrl" => "http://localhost:8080/checkout/complete/1",
        ]);

        return response()->redirect($payment->getCheckoutUrl(), 303);
    }

    /**
     * Callback route to be called from PSP (Mollie)
     *
     * @param Cart $cart
     * @param $order
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function callback(Cart $cart, $order)
    {
        $cart->clear();

        return response()->twig('checkout/complete.twig', [
            'order' => $order
        ]);
    }
}