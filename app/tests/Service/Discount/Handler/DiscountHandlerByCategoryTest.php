<?php

namespace App\Tests\Service\Discount\Handler;

use App\Entity\Category;
use App\Entity\Product;
use App\Service\Discount\Handler\DiscountHandlerByCategory;
use PHPUnit\Framework\TestCase;

class DiscountHandlerByCategoryTest extends TestCase
{
    private DiscountHandlerByCategory $service;

    public function setUp(): void
    {
        $this->service = new DiscountHandlerByCategory();
    }

    public function testIsCheckTrue()
    {
        $productSku = 'sku_test';
        $productName = 'name_test';
        $productPrice = 10000;

        $categoryName = 'boots';
        $category = (new Category())
            ->setName($categoryName);

        $product = (new Product())
            ->setSku($productSku)
            ->setName($productName)
            ->setPrice($productPrice)
            ->setCategory($category);

        $check = $this->service->check($product);
        $this->assertTrue($check);
    }

    public function testIsCheckFailed()
    {
        $productSku = 'sku_test';
        $productName = 'name_test';
        $productPrice = 10000;

        $categoryName = 'other';
        $category = (new Category())
            ->setName($categoryName);

        $product = (new Product())
            ->setSku($productSku)
            ->setName($productName)
            ->setPrice($productPrice)
            ->setCategory($category);

        $check = $this->service->check($product);
        $this->assertFalse($check);
    }

    public function testGetDiscountAmount()
    {
        $expectedAmount = 30;

        $this->assertEquals($expectedAmount, $this->service->getDiscountAmount());
    }
}
