<?php

namespace App\Tests\Service\Discount;

use App\Dto\Product\Response\OfferDto;
use App\Dto\Product\Response\PriceDto;
use App\Entity\Category;
use App\Entity\Product;
use App\Enum\Currency;
use App\Service\Discount\Handler\DiscountHandlerByCategory;
use App\Service\Discount\Handler\DiscountHandlerBySku;
use App\Service\Discount\Manager;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    public function testCalculateDiscountsForProducts()
    {
        $expectedOfferDtos = $this->offerDataProvider();
        $productList = $this->productDataProvider();
        $service = new Manager($this->discountDataProvider());

        $offerDtoList = $service->calculateDiscountsForProducts(...$productList);

        $this->assertCount(4, $offerDtoList);

        foreach ($expectedOfferDtos as $i => $offerDto) {
            $this->assertEquals($offerDto, $offerDtoList[$i]);
        }
    }

    private function discountDataProvider(): iterable
    {
        $expectedDiscountBySku = 15.0;
        $expectedDiscountByCategory = 30.0;

        $discountBySkuMock = $this->createMock(DiscountHandlerBySku::class);
        $discountBySkuMock
            ->expects(self::exactly(4))
            ->method('check')
            ->willReturnOnConsecutiveCalls(true, false, true, false);
        $discountBySkuMock
            ->expects(self::exactly(2))
            ->method('getDiscountAmount')
            ->willReturn($expectedDiscountBySku);

        $discountByCategoryMock = $this->createMock(DiscountHandlerByCategory::class);
        $discountByCategoryMock
            ->expects(self::exactly(4))
            ->method('check')
            ->willReturnOnConsecutiveCalls(false, true, true, false);
        $discountByCategoryMock
            ->expects(self::exactly(2))
            ->method('getDiscountAmount')
            ->willReturn($expectedDiscountByCategory);

        return [$discountBySkuMock, $discountByCategoryMock];
    }

    private function productDataProvider(): array
    {
        $skuMarker = '000003';
        $categoryMarker = 'boots';

        $mockCategoryOther = $this->createMock(Category::class);
        $mockCategoryOther
            ->expects(self::exactly(2))
            ->method('getName')
            ->willReturn('test_category_other');

        $mockCategoryBoots = $this->createMock(Category::class);
        $mockCategoryBoots
            ->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($categoryMarker);

        $mockProductSkuMarker = $this->createMock(Product::class);
        $mockProductSkuMarker
            ->expects(self::once())
            ->method('getSku')
            ->willReturn($skuMarker);
        $mockProductSkuMarker
            ->expects(self::once())
            ->method('getName')
            ->willReturn('test_name_1');
        $mockProductSkuMarker->expects(self::once())
            ->method('getPrice')
            ->willReturn(1000);
        $mockProductSkuMarker
            ->expects(self::once())
            ->method('getCategory')
            ->willReturn($mockCategoryOther);

        $mockProductCategoryMarker = $this->createMock(Product::class);
        $mockProductCategoryMarker
            ->expects(self::once())
            ->method('getSku')
            ->willReturn('test_sku_2');
        $mockProductCategoryMarker
            ->expects(self::once())
            ->method('getName')
            ->willReturn('test_name_2');
        $mockProductCategoryMarker
            ->expects(self::once())
            ->method('getPrice')
            ->willReturn(2000);
        $mockProductCategoryMarker
            ->expects(self::once())
            ->method('getCategory')
            ->willReturn($mockCategoryBoots);

        $mockProductBoth = $this->createMock(Product::class);
        $mockProductBoth
            ->expects(self::once())
            ->method('getSku')
            ->willReturn($skuMarker);
        $mockProductBoth
            ->expects(self::once())
            ->method('getName')
            ->willReturn('test_name_3');
        $mockProductBoth
            ->expects(self::once())
            ->method('getPrice')
            ->willReturn(3000);
        $mockProductBoth
            ->expects(self::once())
            ->method('getCategory')
            ->willReturn($mockCategoryBoots);

        $mockProductOther = $this->createMock(Product::class);
        $mockProductOther
            ->expects(self::once())
            ->method('getSku')
            ->willReturn('test_sku_4');
        $mockProductOther
            ->expects(self::once())
            ->method('getName')
            ->willReturn('test_name_4');
        $mockProductOther
            ->expects(self::once())
            ->method('getPrice')
            ->willReturn(4000);
        $mockProductOther
            ->expects(self::once())
            ->method('getCategory')
            ->willReturn($mockCategoryOther);

        return [$mockProductSkuMarker, $mockProductCategoryMarker, $mockProductBoth, $mockProductOther];
    }

    private function offerDataProvider(): array
    {
        return [
            new OfferDto('000003', 'test_name_1', 'test_category_other',
                (new PriceDto(1000, 850, '15%', Currency::Euro))
            ),
            new OfferDto('test_sku_2', 'test_name_2', 'boots',
                (new PriceDto(2000, 1400, '30%', Currency::Euro))
            ),
            new OfferDto('000003', 'test_name_3', 'boots',
                (new PriceDto(3000, 2100, '30%', Currency::Euro))
            ),
            new OfferDto('test_sku_4', 'test_name_4', 'test_category_other',
                (new PriceDto(4000, 4000, null, Currency::Euro))
            ),
        ];
    }
}
