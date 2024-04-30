<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\Inventory;

class AddItemCommand
{
    public function __construct(
        private readonly string $userId,
        private readonly string $cartId,
        private readonly string $itemId,
        private readonly string $productId,
        private readonly float $amount
    ) {
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function itemId(): string
    {
        return $this->itemId;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function amount(): float
    {
        return $this->amount;
    }
}
