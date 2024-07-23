<?php

namespace App\Service;

use App\Dto\Product\QueryDto;
use App\Dto\Product\Response\OfferDto;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Discount\Manager as DiscountManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

readonly class ProductSearcher
{
    public function __construct(
        private ProductRepository  $productRepository,
        private CategoryRepository $categoryRepository,
        private DiscountManager    $discountManager
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

        $intPrice = $this->preparePrice($queryDto->priceLessThan);

        $productList = $this->productRepository->findAllByCategoryAndPriceLessThanOrEqual(
            $queryDto->page,
            $queryDto->limit,
            $category,
            $intPrice
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
