<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param Category|null $category
     * @param int|null $priceLessThan
     * @return array
     */
    public function findAllByCategoryAndPriceLessThanOrEqual(int $page, int $limit, ?Category $category, ?int $priceLessThan): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder
            ->setMaxResults($limit)
            ->setFirstResult($limit*($page-1));

        if ($category) {
            $queryBuilder
                ->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        if ($priceLessThan) {
            $queryBuilder
                ->andWhere('p.price <= :price')
                ->setParameter('price', $priceLessThan);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }


}
