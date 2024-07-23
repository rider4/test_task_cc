<?php

namespace App\Service\Discount\Handler;

use App\Entity\Product;

interface DiscountHandlerInterface
{
    public function check(Product $product): bool;

    /**
     * Return amount of discount in percents
     *
     * @return float
     * @example 20
     */
    public function getDiscountAmount(): float;
}
