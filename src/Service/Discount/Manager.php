<?php

namespace App\Service\Discount;

use App\Dto\Product\Response\OfferDto;
use App\Dto\Product\Response\PriceDto;
use App\Entity\Product;
use App\Service\Discount\Handler\DiscountHandlerInterface;

class Manager
{
    /**
     * @var DiscountHandlerInterface[]
     */
    private iterable $discountHandlerList;

    public function __construct(iterable $discountHandlerList)
    {
        $this->discountHandlerList = $discountHandlerList;
    }

    /**
     * @param Product ...$products
     * @return OfferDto[]
     */
    public function calculateDiscountsForProducts(Product...$products): array
    {
        $res = [];

        foreach ($products as $product) {
            $res[] = new OfferDto(
                $product->getSku(),
                $product->getName(),
                $product->getCategory()->getName(),
                $this->preparePriceDto($product)
            );
        }

        return $res;
    }

    private function preparePriceDto(Product $product): PriceDto
    {
        $discountsToApply = [];
        $maxDiscount = 0.0;
        foreach ($this->discountHandlerList as $discount) {
            if ($discount->check($product)) {
                $discountAmount = $discount->getDiscountAmount();

                $discountsToApply[get_class($discount)] = $discountAmount;

                $maxDiscount = $discountAmount > $maxDiscount
                    ? $discountAmount
                    : $maxDiscount;
            }
        }

        return new PriceDto(
            $product->getPrice(),
            $this->calculateFinalPrice($product->getPrice(), $maxDiscount),
            $maxDiscount ? sprintf('%d%%', $maxDiscount) : null
        );
    }

    private function calculateFinalPrice(int $originPrice, float $discount): int
    {
        $priceAsFloat = $originPrice / 100;

        $res = $discount > 0
            ? $priceAsFloat * (1 - ($discount / 100))
            : $priceAsFloat;

        return (int)($res * 100);
    }
}
