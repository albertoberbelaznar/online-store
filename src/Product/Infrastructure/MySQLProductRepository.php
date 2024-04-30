<?php

declare(strict_types=1);

namespace Online\Store\Product\Infrastructure;

use Doctrine\DBAL\Connection;
use Online\Store\Product\Domain\Product;
use Online\Store\Product\Domain\ProductCollection;
use Online\Store\Product\Domain\ProductFactory;
use Online\Store\Product\Domain\ProductRepository;
use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\ProductType;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class MySQLProductRepository implements ProductRepository
{
    private const TABLE_PRODUCT = 'product';

    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws InternalErrorException
     */
    public function save(Product $product): void
    {
        $storedProduct = $this->findById($product->id());

        if (!$storedProduct) {
            $this->insert($product);

            return;
        }

        $this->update($product);
    }

    /**
     * @throws InternalErrorException
     */
    public function remove(Uuid $productId): void
    {
        try {
            $this->connection->delete(
                self::TABLE_PRODUCT,
                ['id' => $productId->value()],
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    public function findAll(): ProductCollection
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->select('*')
                ->from(self::TABLE_PRODUCT)
                ->fetchAllAssociative();

            return $this->fromDbToProductCollection($result);
        } catch (\Throwable $e) {
            throw new InternalErrorException('Query error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    public function getAvailableAmount(Uuid $productId): float
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->select('amount')
                ->from(self::TABLE_PRODUCT)
                ->where('id = :id')
                ->setParameter('id', $productId->value())
                ->fetchAssociative();

            if (!$result) {
                return 0;
            }

            return (float) $result['amount'];
        } catch (\Throwable $e) {
            throw new InternalErrorException('Query error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    public function findById(Uuid $productId): ?Product
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->select('*')
                ->from(self::TABLE_PRODUCT)
                ->where('id = :id')
                ->setParameter('id', $productId->value())
                ->fetchAssociative();

            if (!$result) {
                return null;
            }

            return $this->fromDataToProduct($result);
        } catch (\Throwable $e) {
            throw new InternalErrorException('Query error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function insert(Product $product): void
    {
        try {
            $this->connection->insert(
                self::TABLE_PRODUCT,
                [
                    'id' => $product->id()->value(),
                    'name' => $product->name()->value(),
                    'price' => $product->price(),
                    'type' => $product->type()->value,
                    'amount' => $product->amount(),
                    'created_at' => $product->createdAt()->stringDateTime(),
                    'updated_at' => $product->updatedAt()?->stringDateTime(),
                ]
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException('Insert error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function update(Product $product): void
    {
        try {
            $this->connection->update(
                self::TABLE_PRODUCT,
                [
                    'name' => $product->name()->value(),
                    'price' => $product->price(),
                    'amount' => $product->amount(),
                    'updated_at' => (new Date())->stringDateTime(),
                ],
                [
                    'id' => $product->id()->value(),
                ],
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }

    /**
     * @param array<int, array<string, mixed>> $productListData
     *
     * @throws InvalidValueException
     */
    private function fromDbToProductCollection(array $productListData): ProductCollection
    {
        $productList = array_map(
            fn (array $productData) => $this->fromDataToProduct($productData),
            $productListData
        );

        return new ProductCollection($productList);
    }

    /**
     * @throws InvalidValueException
     */
    private function fromDataToProduct(array $productData): Product
    {
        return ProductFactory::build(ProductType::from($productData['type']))->getProduct(
            new Uuid($productData['id']),
            $productData
        );
    }
}
