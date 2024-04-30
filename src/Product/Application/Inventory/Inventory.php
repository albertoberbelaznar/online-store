<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\Inventory;

use Online\Store\Product\Domain\ProductCollection;
use Online\Store\Product\Domain\ProductFactory;
use Online\Store\Product\Domain\ProductRepository;
use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\ProductType;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class Inventory
{
    public function __construct(
        private readonly ProductRepository $repository,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws InternalErrorException
     * @throws InvalidValueException
     */
    public function addProduct(Uuid $id, array $data): void
    {
        $product = ProductFactory::build(ProductType::from((int) $data['type']))->getProduct($id, $data);

        $this->repository->save($product);
    }

    /**
     * @throws InternalErrorException
     */
    public function removeProduct(Uuid $productId): void
    {
        $this->repository->remove($productId);
    }

    /**
     * @throws InternalErrorException
     */
    public function getAllProducts(): ProductCollection
    {
        return $this->repository->findAll();
    }
}
