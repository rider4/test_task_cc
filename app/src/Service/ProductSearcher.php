<?php

namespace App\Service;

use App\Dto\Product\QueryDto;
use App\Dto\Product\Response\OfferDto;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

readonly class ProductSearcher
{
    public function __construct(
        private ProductRepository  $productRepository,
        private CategoryRepository $categoryRepository,
        private Discount\Manager   $discountManager
    )
    {
    }

    /**
     * @param QueryDto $queryDto
     * @return OfferDto[]
     */
    public function search(QueryDto $queryDto): array
    {
        $category = null;
        if ($queryDto->category) {
            $category = $this->categoryRepository->findOneByName($queryDto->category);
        }

        $prepareToDBPriceFormat = $this->preparePrice($queryDto->priceLessThan);

        $productList = $this->productRepository->findAllByCategoryAndPriceLessThanOrEqual(
            $queryDto->page,
            $queryDto->limit,
            $category,
            $prepareToDBPriceFormat
        );

        return $this->discountManager->calculateDiscountsForProducts(...$productList);
    }

    private function preparePrice(?float $priceLessThan): ?int
    {
        if (is_null($priceLessThan)) {
            return null;
        }

        return (int)($priceLessThan * 100);
    }
}
