<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\Name;
use Online\Store\Shared\Domain\ValueObject\ProductType;
use Online\Store\Shared\Domain\ValueObject\Uuid;

abstract class Product implements PricingStrategy
{
    protected function __construct(
        private readonly Uuid $id,
        private readonly Name $name,
        private readonly float $price,
        private readonly ProductType $type,
        private readonly float $amount,
        private readonly ?Date $createdAt = new Date(),
        private readonly ?Date $updatedAt = null
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function type(): ProductType
    {
        return $this->type;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function createdAt(): ?Date
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
            'name' => $this->name()->value(),
            'price' => $this->price(),
            'type' => $this->type()->value,
            'amount' => $this->amount(),
            'created_at' => $this->createdAt()->stringDateTime(),
            'updated_at' => $this->updatedAt()?->stringDateTime(),
        ];
    }
}
