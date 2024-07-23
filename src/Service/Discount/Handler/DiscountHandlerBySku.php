<?php

namespace App\Service\Discount\Handler;

use App\Entity\Product;

class DiscountHandlerBySku implements DiscountHandlerInterface
{
    private const SKU = '000003';

    public function check(Product $product): bool
    {
        return $product->getSku() == self::SKU;
    }

    public function getDiscountAmount(): float
    {
        return 15;
    }
}
