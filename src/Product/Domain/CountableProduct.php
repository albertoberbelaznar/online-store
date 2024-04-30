<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\Name;
use Online\Store\Shared\Domain\ValueObject\ProductType;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class CountableProduct extends Product
{
    public function __construct(Uuid $id, Name $name, float $price, float $amount, ?Date $createdAt, ?Date $updatedAt)
    {
        parent::__construct(
            $id,
            $name,
            $price,
            ProductType::COUNTABLE,
            $amount,
            $createdAt,
            $updatedAt
        );
    }

    public function calculatePrice(float $amount): float
    {
        return $amount * $this->price();
    }
}
