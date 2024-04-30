<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class ShoppingCart
{
    public function __construct(
        private readonly Uuid $id,
        private readonly Uuid $userId,
        private readonly ?Date $createdAt = new Date(),
        private readonly ?Date $updatedAt = null,
        private CartItemCollection $items = new CartItemCollection([]),
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function userId(): Uuid
    {
        return $this->userId;
    }

    public function createdAt(): Date
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?Date
    {
        return $this->updatedAt;
    }

    public function items(): CartItemCollection
    {
        return $this->items;
    }

    public function addItem(CartItem $newItem): self
    {
        $items = [];
        /** @var CartItem $item */
        foreach ($this->items->items() as $item) {
            if (!$item->id()->equals($newItem->id())) {
                $items[] = $item;
            }
        }
        $items[] = $newItem;

        $this->items = new CartItemCollection($items);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        return [
            'id' => $this->id()->value(),
            'user_id' => $this->userId()->value(),
            'created_at' => $this->createdAt()->stringDateTime(),
            'updated_at' => $this->updatedAt()?->stringDateTime(),
            'item_list' => $this->items()->serialize(),
        ];
    }
}
