<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Render the home page
     */
    public function index()
    {
        $new = $this->getNewArrivals();
        $categories = $this->manager->createQueryBuilder()
            ->select('s')
            ->from('App\Entities\StockGroup', 's')
            ->where('s.ImagePath IS NOT NULL')
            ->getQuery()
            ->getResult();

        return response()->twig('index.twig', [
            'new' => $new,
            'categories' => $categories
        ]);
    }

    private function getNewArrivals()
    {
        $q = $this->manager->createQueryBuilder();

        $q->select('s')
            ->from('App\Entities\StockItem', 's')
            ->orderBy('s.ValidFrom', 'ASC')
            ->setMaxResults(8);

        return $q->getQuery()->getResult();
    }
}