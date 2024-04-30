<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class CartItem
{
    public function __construct(
        private readonly Uuid $id,
        private readonly Uuid $shoppingCartId,
        private readonly Product $product,
        private readonly float $amount,
        private readonly ? Date $createdAt = new Date(),
        private readonly ? Date $updatedAt = null
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function shoppingCartId(): Uuid
    {
        return $this->shoppingCartId;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function createdAt(): Date
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?Date
    {
        return $this->updatedAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        return [
            'id' => $this->id()->value(),
            'shopping_cart_id' => $this->shoppingCartId()->value(),
            'product' => $this->product()->serialize(),
            'price' => $this->product->calculatePrice($this->amount()),
            'amount' => $this->amount(),
            'created_at' => $this->createdAt()->stringDateTime(),
            'updated_at' => $this->updatedAt()?->stringDateTime(),
        ];
    }
}
