<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\Collection;

/** @extends Collection<int, CartItem> */
class CartItemCollection extends Collection
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function serialize(): array
    {
        return array_map(
            static fn (CartItem $item) => $item->serialize(),
            (array) $this->items()
        );
    }

    protected function type(): string
    {
        return CartItem::class;
    }
}
