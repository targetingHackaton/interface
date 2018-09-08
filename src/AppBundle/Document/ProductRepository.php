<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ProductRepository extends DocumentRepository
{
    /**
     * @param array $productIds
     * @return mixed|array|Product[]
     */
    public function getProducts(array $productIds): array
    {
        return $this->createQueryBuilder()
            ->field('productId')->in($productIds)
            ->getQuery()
            ->getIterator()
            ->toArray();
    }
}
