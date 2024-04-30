<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\ValueObject\Uuid;

interface ProductRepository
{
    /**
     * @throws InternalErrorException
     */
    public function save(Product $product): void;

    /**
     * @throws InternalErrorException
     */
    public function remove(Uuid $productId): void;

    /**
     * @throws InternalErrorException
     */
    public function findAll(): ProductCollection;

    /**
     * @throws InternalErrorException
     */
    public function getAvailableAmount(Uuid $productId): float;

    /**
     * @throws InternalErrorException
     */
    public function findById(Uuid $productId): ?Product;
}
