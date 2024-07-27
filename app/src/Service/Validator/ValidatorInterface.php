<?php

namespace App\Service\Validator;

use App\Dto\Product\RequestDtoInterface;

interface ValidatorInterface
{
    /**
     * @param RequestDtoInterface $dto
     * @return array
     * @example ['error_field_1' => 'error msg',]
     */
    public function validate(RequestDtoInterface $dto): array;
}
