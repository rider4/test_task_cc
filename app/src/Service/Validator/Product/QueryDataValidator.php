<?php

namespace App\Service\Validator\Product;

use App\Dto\Product\QueryDto;
use App\Dto\Product\RequestDtoInterface;
use App\Service\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator;

class QueryDataValidator implements ValidatorInterface
{
    private const LAZY_LOAD_VALIDATOR_METADATA_METHOD_NAME = 'lazyLoadValidatorMetadata';
    private readonly Validator\ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = $this->getValidator();
    }

    /**
     * @param RequestDtoInterface|QueryDto $dto
     * @return array
     */
    public function validate(RequestDtoInterface $dto): array
    {
        $violationList = $this->validator->validate($dto);

        $errors = [];
        if ($violationList->count()) {
            foreach ($violationList as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }

        return $errors;
    }

    /**
     * @return Validator\ValidatorInterface
     */
    public function getValidator(): Validator\ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->addMethodMapping(self::LAZY_LOAD_VALIDATOR_METADATA_METHOD_NAME)
            ->getValidator();
    }
}
