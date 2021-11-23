<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Render the homepage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
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

    /**
     * Get new arrivals
     *
     * @return int|mixed|string
     */
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