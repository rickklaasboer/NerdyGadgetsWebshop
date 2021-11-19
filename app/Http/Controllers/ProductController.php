<?php

namespace App\Http\Controllers;

use App\Entities\StockGroup;
use App\Entities\StockItem;
use App\Exceptions\Http\HttpNotFoundException;
use App\Util\Paginator;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;

class ProductController extends Controller
{
    /**
     * The browse page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $categories = $this->manager->createQueryBuilder()
            ->select('s')
            ->from(StockGroup::class, 's')
            ->getQuery()
            ->getResult();

        $repository = $this->manager->getRepository(StockItem::class);

        $count = (int)$this->applySorting($this->applyFilters($repository->createQueryBuilder('s')
            ->select('count(s.StockItemID)')))
            ->getQuery()
            ->getSingleScalarResult();

        $currentPage = $this->request->get('page', 1);

        $paginator = new Paginator($count, 25, $currentPage);

        $q = $this->manager->createQueryBuilder()
            ->select('s')
            ->from(StockItem::class, 's');

        $q = $this->applyFilters($q);

        $q = $this->applySorting($q);

        $q->setMaxResults(25);
        $q->setFirstResult(($currentPage - 1) * 25);

        $stockItems = $q->getQuery()->getResult();

        return response()->twig('browse.twig', [
            'categories' => $categories,
            'stockItems' => $stockItems,
            'paginator' => $paginator,
            'count' => $count,
        ]);
    }

    /**
     * Show a single stockitem
     *
     * @param $id
     * @return string
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show($id)
    {
        $stockItem = $this->manager->find('App\Entities\StockItem', $id);

        if (!$stockItem) {
            throw new HttpNotFoundException();
        }

        $relatedStockItems = array_slice($stockItem->getStockGroups()->first()->getStockItems()->toArray(), 0, 6);

        return response()->twig('product.twig', [
            'stockItem' => $stockItem,
            'relatedStockItems' => $relatedStockItems,
        ]);
    }

    /**
     * Apply filters to browse query
     *
     * @param QueryBuilder $builder
     * @return QueryBuilder
     */
    private function applyFilters(QueryBuilder $builder): QueryBuilder
    {
        if ($this->request->get('query')) {
            $query = $this->request->get('query');

            $builder->andWhere("s.StockItemName LIKE :s1 OR s.SearchDetails LIKE :s2 OR s.StockItemID LIKE :s3")
                ->setParameter('s1', "%{$query}%")
                ->setParameter('s2', "%{$query}%")
                ->setParameter('s3', "%{$query}%");
        }

        if ($this->request->get('price_min')) {
            $min = $this->request->get('price_min') * 1.21;
            $builder->andWhere('s.RecommendedRetailPrice >= :min')
                ->setParameter('min', $min);
        }

        if ($this->request->get('price_max')) {
            $max = $this->request->get('price_max') / 1.21;
            $builder->andWhere('s.RecommendedRetailPrice <= :max')
                ->setParameter('max', $max);
        }

        if ($this->request->get('category_id')) {
            $builder->join('s.StockGroups', 'sg');

            $categories = array_wrap($this->request->get('category_id'));

            $builder->andWhere($builder->expr()->in('sg.StockGroupID', ':ids'))
                ->setParameter('ids', $categories, Connection::PARAM_INT_ARRAY);
        }

        return $builder;
    }

    /**
     * Apply sorting to browse query
     *
     * @param QueryBuilder $builder
     * @return QueryBuilder
     */
    private function applySorting(QueryBuilder $builder): QueryBuilder
    {
        match ($this->request->get('sort', 0)) {
            'alphabetic_desc' => $builder->orderBy('s.StockItemName', 'DESC'),
            'alphabetic_asc' => $builder->orderBy('s.StockItemName', 'ASC'),
            'price_desc' => $builder->orderBy('s.RecommendedRetailPrice', 'DESC'),
            'price_asc' => $builder->orderBy('s.RecommendedRetailPrice', 'ASC'),
            default => $builder->orderBy('s.StockItemName', 'ASC'),
        };

        return $builder;
    }
}