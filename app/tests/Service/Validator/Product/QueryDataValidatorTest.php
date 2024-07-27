<?php

namespace App\Tests\Service\Validator\Product;

use App\Dto\Product\QueryDto;
use App\Service\Validator\Product\QueryDataValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class QueryDataValidatorTest extends TestCase
{
    public function testConstructCheckValidatorOptions()
    {
        $expectedMethodName = 'lazyLoadValidatorMetadata';

        $service = new QueryDataValidator();
        $validator = $this->getValueOfPrivateProperty($service::class, 'validator', $service);
        $metadataFactory = $this->getValueOfPrivateProperty(RecursiveValidator::class, 'metadataFactory', $validator);
        $loader = $this->getValueOfPrivateProperty(LazyLoadingMetadataFactory::class, 'loader', $metadataFactory);
        $methodName = $this->getValueOfPrivateProperty(StaticMethodLoader::class, 'methodName', $loader);

        $this->assertEquals($expectedMethodName, $methodName);
    }

    /**
     * @dataProvider queryDtoListProviderWithoutViolations
     * @param QueryDto $dto
     * @return void
     */
    public function testValidateWithoutViolations(QueryDto $dto)
    {
        $expectedResult = [];
        $service = new QueryDataValidator();

        $result = $service->validate($dto);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return \Generator
     */
    public static function queryDtoListProviderWithoutViolations(): \Generator
    {
        yield [(new QueryDto())];
        yield [(new QueryDto('test_category'))];
        yield [(new QueryDto('test_category', 10.50))];
        yield [(new QueryDto('test_category', 10.50, 5))];
        yield [(new QueryDto('test_category', 10.50, 5, 3))];
    }

    /**
     * @dataProvider queryDtoListProviderWithViolations
     * @param QueryDto $dto
     * @param array $expectedResult
     * @return void
     */
    public function testValidateWithViolations(QueryDto $dto, string $expectedResult)
    {
        $service = new QueryDataValidator();

        $result = $service->validate($dto);

        $this->assertEquals($expectedResult, json_encode($result));
    }

    /**
     * @return \Generator
     */
    public static function queryDtoListProviderWithViolations(): \Generator
    {
        yield [(new QueryDto('test_category', -0.01)), '{"priceLessThan":["This value should be greater than or equal to 0."]}'];
        yield [(new QueryDto('test_category', 10.50, 0)), '{"limit":["This value should be greater than or equal to 1."]}'];
        yield [(new QueryDto('test_category', 10.50, 6)), '{"limit":["This value should be less than or equal to 5."]}'];
        yield [(new QueryDto('test_category', 10.50, 5, 0)), '{"page":["This value should be greater than or equal to 1."]}'];
    }


    /**
     * @param string $className
     * @param string $propertyName
     * @param object $instance
     * @return mixed
     * @throws \ReflectionException
     */
    private function getValueOfPrivateProperty(string $className, string $propertyName, object $instance): mixed
    {
        $refService = new \ReflectionClass($className);
        $reflectionProperty = $refService->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($instance);
    }
}
