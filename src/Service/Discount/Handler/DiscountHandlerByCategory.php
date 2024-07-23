<?php

namespace App\Service\Discount\Handler;

use App\Entity\Product;

class DiscountHandlerByCategory implements DiscountHandlerInterface
{
    public function check(Product $product): bool
    {
        return $product->getCategory()->getName() == Product::CATEGORY_BOOTS;
    }

    public function getDiscountAmount(): float
    {
        return 30;
    }
}
