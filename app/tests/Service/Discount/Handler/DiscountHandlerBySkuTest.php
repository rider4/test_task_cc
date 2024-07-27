<?php

namespace App\Tests\Service\Discount\Handler;

use App\Entity\Category;
use App\Entity\Product;
use App\Service\Discount\Handler\DiscountHandlerBySku;
use PHPUnit\Framework\TestCase;

class DiscountHandlerBySkuTest extends TestCase
{
    private DiscountHandlerBySku $service;

    public function setUp(): void
    {
        $this->service = new DiscountHandlerBySku();
    }

    public function testIsCheckTrue()
    {
        $productSku = '000003';
        $productName = 'name_test';
        $productPrice = 10000;

        $categoryName = 'category_name_test';
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
        $productSku = 'other';
        $productName = 'name_test';
        $productPrice = 10000;

        $categoryName = 'category_name_test';
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
        $expectedAmount = 15;

        $this->assertEquals($expectedAmount, $this->service->getDiscountAmount());
    }
}
