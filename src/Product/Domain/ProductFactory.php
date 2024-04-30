<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\Name;
use Online\Store\Shared\Domain\ValueObject\ProductType;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class ProductFactory
{
    public function __construct(protected readonly ProductType $type)
    {
    }

    public static function build(ProductType $type): ProductFactory
    {
        return new self($type);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws InvalidValueException
     */
    public function getProduct(Uuid $id, array $data): Product
    {
        if ($this->type === ProductType::COUNTABLE) {
            return new CountableProduct(
                $id,
                new Name($data['name']),
                (float) $data['price'],
                (float) $data['amount'],
                isset($data['created_at']) ? new Date($data['created_at']) : new Date(),
                isset($data['updated_at']) ? new Date($data['updated_at']) : null,
            );
        }

        if ($this->type === ProductType::WEIGHTED) {
            return new WeightedProduct(
                $id,
                new Name($data['name']),
                (float) $data['price'],
                (float) $data['amount'],
                isset($data['created_at']) ? new Date($data['created_at']) : new Date(),
                isset($data['updated_at']) ? new Date($data['updated_at']) : null,
            );
        }
    }
}
