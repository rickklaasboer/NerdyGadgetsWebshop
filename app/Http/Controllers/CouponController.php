<?php

namespace App\Http\Controllers;

use App\Entities\Coupon;
use App\Util\Validation\Rules\Exists;
use App\Util\Validation\Rules\Min;
use App\Util\Validation\Rules\Required;
use App\Util\Validation\Validator;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class CouponController extends Controller
{
    /**
     * Apply coupon to cart
     *
     * @param Request $request
     * @param EntityManager $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function apply(Request $request, EntityManager $em)
    {
        $form = ['coupon' => $request->get('coupon')];

        $validator = new Validator($form, [
            'coupon' => [new Required(), new Min(1), new Exists(Coupon::class, 'code')],
        ]);

        if ($validator->fails()) {
            $request->getSession()->getFlashBag()->add('errors', $validator->messages());

            return response()->redirect(url()->prev());
        }

        /** @var Coupon $coupon */
        $coupon = $em->createQueryBuilder()
            ->select('c')
            ->from(Coupon::class, 'c')
            ->where('c.code = :code')
            ->setParameter('code', $form['coupon'])
            ->getQuery()
            ->getResult()[0];

        if ($coupon->getEndDate() < Carbon::now()) {
            $request->getSession()->getFlashBag()->add('errors', ['Coupon has expired']);
            return response()->redirect(url()->prev());
        }

        $request->getSession()
            ->set('discount', $coupon->getDiscount());

        return response()->redirect(url()->prev());
    }

    /**
     * Unapply coupon from cart
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function unapply(Request $request)
    {
        if ($request->getSession()->has('discount')) {
            $request->getSession()->remove('discount');
        }

        return response()->redirect(url()->prev());
    }
}