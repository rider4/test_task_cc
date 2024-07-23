<?php

namespace App\Tests\Service;

use App\Dto\Product\QueryDto;
use App\Dto\Product\Response\OfferDto;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Discount\Manager;
use App\Service\ProductSearcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductSearcherTest extends TestCase
{
    /**
     * @dataProvider queryDtoListProvider
     * @param QueryDto $queryDto
     * @return void
     */
    public function testSearch(QueryDto $queryDto)
    {
        $expectedOfferList = $this->offerListMockProvider();
        /** @var MockObject|Category $categoryMock */
        $categoryMock = $this->createMock(Category::class);

        $service = new ProductSearcher(
            $this->getProductRepositoryMock($queryDto, $categoryMock),
            $this->getCategoryRepositoryMock($queryDto->category, $categoryMock),
            $this->getDiscountManagerMock(...$this->productListMockProvider())
        );

        $offerDtos = $service->search($queryDto);

        $this->assertEquals($expectedOfferList, $offerDtos);
    }

    /**
     * @return QueryDto[]
     */
    public static function queryDtoListProvider(): iterable
    {
        yield [(new QueryDto())];
        yield [(new QueryDto('test_category'))];
        yield [(new QueryDto('test_category', 10.50))];
        yield [(new QueryDto('test_category', 10.50, 12))];
        yield [(new QueryDto('test_category', 10.50, 12, 3))];
    }

    /**
     * @param string|null $category
     * @param MockObject|Category $categoryMock
     * @return MockObject|CategoryRepository
     */
    private function getCategoryRepositoryMock(?string $category, MockObject $categoryMock): ?MockObject
    {
        $mock = $this->createMock(CategoryRepository::class);

        if (is_null($category)) {
            $mock
                ->expects(self::never())
                ->method('findOneByName');
        } else {
            $mock
                ->expects(self::exactly(1))
                ->method('findOneByName')
                ->with($category)
                ->willReturn($categoryMock);
        }

        return $mock;
    }

    /**
     * @param QueryDto $queryDto
     * @param MockObject|Category|null $categoryMock
     * @return MockObject|ProductRepository
     */
    private function getProductRepositoryMock(QueryDto $queryDto, ?MockObject $categoryMock): MockObject
    {
        $mock = $this->createMock(ProductRepository::class);
        $category = $queryDto->category ? $categoryMock : null;
        $mock
            ->expects(self::exactly(1))
            ->method('findAllByCategoryAndPriceLessThanOrEqual')
            ->with($queryDto->page, $queryDto->limit, $category, $this->preparePrice($queryDto->priceLessThan))
            ->willReturn($this->productListMockProvider());

        return $mock;
    }

    /**
     * @param Product ...$productList
     * @return MockObject|Manager
     */
    private function getDiscountManagerMock(Product...$productList): MockObject
    {
        $mock = $this->createMock(Manager::class);
        $mock
            ->expects(self::exactly(1))
            ->method('calculateDiscountsForProducts')
            ->with(...$productList)
            ->willReturn($this->offerListMockProvider());

        return $mock;
    }

    /**
     * @return MockObject[]|Product[]
     */
    private function productListMockProvider(): array
    {
        return [
            $this->createMock(Product::class),
            $this->createMock(Product::class),
            $this->createMock(Product::class),
            $this->createMock(Product::class),
        ];
    }

    /**
     * @return MockObject[]|OfferDto[]
     */
    private function offerListMockProvider(): array
    {
        return [
            $this->createMock(OfferDto::class),
            $this->createMock(OfferDto::class),
            $this->createMock(OfferDto::class),
            $this->createMock(OfferDto::class),
        ];
    }

    /**
     * @param float|null $value
     * @return int|null
     */
    private function preparePrice(?float $value): ?int
    {
        if (is_null($value)) {
            return null;
        }

        return (int)($value * 100);
    }
}
