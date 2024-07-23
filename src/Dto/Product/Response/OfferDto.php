<?php

namespace App\Dto\Product\Response;

readonly class OfferDto
{
    public function __construct(
        public string   $sku,
        public string   $name,
        public string   $category,
        public PriceDto $price,
    )
    {
    }
}
