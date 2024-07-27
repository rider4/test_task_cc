<?php

namespace App\Dto\Product;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

readonly class QueryDto implements RequestDtoInterface
{
    /**
     * @param string|null $category
     * @param float|null $priceLessThan 10.25
     * @param int|null $limit
     * @param int|null $page
     */
    public function __construct(
        public ?string $category = null,
        public ?float  $priceLessThan = null,
        public ?int    $limit = 5,
        public ?int    $page = 1
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
