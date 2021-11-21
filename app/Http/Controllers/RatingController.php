<?php

namespace App\Http\Controllers;

use App\Entities\Rating;
use App\Entities\StockItem;
use App\Entities\User;
use App\Exceptions\Http\HttpForbiddenException;
use App\Util\Cart;
use App\Util\Validation\Builder;
use App\Util\Validation\Validator;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class RatingController extends Controller
{
    public function __construct(Environment $twig, Cart $cart, EntityManager $manager, Request $request)
    {
        parent::__construct($twig, $cart, $manager, $request);

        if (!auth()->isLoggedIn()) {
            throw new HttpForbiddenException();
        }
    }

    /**
     * Store a new rating
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     */
    public function store(Request $request, EntityManager $em, $stockItem)
    {
        $form = \App\Util\Request::from($request)
            ->merge(['stockItem' => $stockItem])
            ->only('rating', 'rating_text', 'stockItem');

        $validator = new Validator($form, [
            'rating' => 'required|between:1,5',
            'rating_text' => 'required|min:1,max:1000',
            'stockItem' => Builder::make()->required()->exists(StockItem::class, 'StockItemID')
        ]);

        if ($validator->fails()) {
            return response()->flash('errors', $validator->messages())
                ->redirect(url()->prev());
        }

        $rating = new Rating();
        $rating->fill([
            'Rating' => $form['rating'],
            'RatingText' => $form['rating_text'],
            'ShouldDisplay' => true,
            'CreatedAt' => Carbon::now(),
        ]);

        // Attach relationships
        $rating->setStockItem(
            $em->getRepository(StockItem::class)->find((int)$stockItem)
        );
        $rating->setUser(
            $em->getRepository(User::class)->find(auth()->getUser()->getId())
        );

        $em->persist($rating);
        $em->flush();

        return response()->redirect(url()->prev());
    }

    /**
     * Update an existing rating
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     */
    public function update(Request $request, $id, $stockItem)
    {
        $form = \App\Util\Request::from($request)
            ->merge(['id' => $id, 'stockItem' => $stockItem])
            ->only('rating', 'rating_text', 'stockItem', 'id');

        $validator = new Validator($form, [
            'rating' => 'required|between:1,5',
            'rating_text' => 'required|min:1,max:1000',
            'stockItem' => Builder::make()->required()->exists(StockItem::class, 'StockItemID'),
            'id' => Builder::make()->required()->exists(Rating::class, 'ID')
        ]);

        if ($validator->fails()) {
            return response()->redirect(url()->prev());
        }

        return response()->redirect(url()->prev());
    }

    /**
     * Delete a rating
     *
     * @param Request $request
     * @param $id
     */
    public function delete(Request $request, $id)
    {

    }
}