<?php

namespace App\Dto\Product;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

readonly class QueryDto
{
    /**
     * @param string|null $category
     * @param float|null $priceLessThan 10.25
     * @param int|null $limit
     * @param int|null $page
     */
    public function __construct(
        public readonly ?string $category = null,
        public readonly ?float  $priceLessThan = null,
        public readonly ?int    $limit = 5,
        public readonly ?int    $page = 1
    )
    {
    }

    public static function lazyLoadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('priceLessThan', new Assert\GreaterThanOrEqual(0));
        $metadata->addPropertyConstraint('page', new Assert\GreaterThanOrEqual(1));
        $metadata->addPropertyConstraint('limit', new Assert\GreaterThanOrEqual(1));
        $metadata->addPropertyConstraint('limit', new Assert\LessThanOrEqual(5));
    }
}
