<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\Name;
use Online\Store\Shared\Domain\ValueObject\ProductType;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class WeightedProduct extends Product
{
    private const DISCOUNT_AMOUNT = 10;
    private const DISCOUNT = 0.1;

    public function __construct(Uuid $id, Name $name, float $price, float $amount, ?Date $createdAt, ?Date $updatedAt)
    {
        parent::__construct(
            $id,
            $name,
            $price,
            ProductType::WEIGHTED,
            $amount,
            $createdAt,
            $updatedAt
        );
    }

    public function calculatePrice(float $amount): float
    {
        $price = $this->price() * $amount;
        if ($this->amount() >= self::DISCOUNT_AMOUNT) {
            $price *= (1 - self::DISCOUNT);
        }

        return $price;
    }
}
