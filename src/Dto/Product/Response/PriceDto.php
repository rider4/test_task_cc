<?php

namespace App\Dto\Product\Response;

use App\Enum\Currency;

readonly class PriceDto
{
    public function __construct(
        public int      $origin,
        public int      $final,
        public ?string  $discount_percentage,
        public Currency $currency = Currency::Euro,
    )
    {
    }
}
