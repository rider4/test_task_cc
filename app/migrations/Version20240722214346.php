<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722214346 extends AbstractMigration
{
    private const PRODUCTS = [
        [
            'sku' => '000001',
            'name' => 'BV Lean leather ankle boots',
            'category' => 'boots',
            'price' => 89000
        ],
        [
            'sku' => '000002',
            'name' => 'BV Lean leather ankle boots',
            'category' => 'boots',
            'price' => 99000
        ],
        [
            'sku' => '000003',
            'name' => 'Ashlington leather ankle boots',
            'category' => 'boots',
            'price' => 71000
        ],
        [
            'sku' => '000004',
            'name' => 'Naima embellished suede sandals',
            'category' => 'sandals',
            'price' => 79500
        ],
        [
            'sku' => '000005',
            'name' => 'Nathane leather sneakers',
            'category' => 'sneakers',
            'price' => 59000
        ]
    ];

    public function getDescription(): string
    {
        return 'Add init data';
    }

    public function up(Schema $schema): void
    {
        $categories = $this->makeCategories();

        foreach (self::PRODUCTS as $item) {
            $this->makeProduct(
                $item['sku'],
                $item['name'],
                $categories[$item['category']],
                $item['price'],
            );
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('delete from category');
    }

    /**
     * @return array [{category: ID},...]
     * @throws \Doctrine\DBAL\Exception
     */
    public function makeCategories(): array
    {
        $categories = [];
        foreach (self::PRODUCTS as $item) {
            $categories[$item['category']] = null;
        }

        foreach ($categories as $key => $category) {
            $this->connection->insert('category', ['name' => $key]);
            $id = $this->connection->lastInsertId();
            $categories[$key] = (int)$id;
        }

        return $categories;
    }

    /**
     * @param string $sku
     * @param string $name
     * @param int $category
     * @param int $price
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    private function makeProduct(string $sku, string $name, int $category, int $price): int
    {
        $this->connection->insert('product', [
            'category_id' => $category,
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
        ]);

        return (int)$this->connection->lastInsertId();
    }
}
