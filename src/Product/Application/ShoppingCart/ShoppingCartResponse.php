<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\ShoppingCart;

use Online\Store\Product\Domain\ShoppingCart;

class ShoppingCartResponse
{
    public function __construct(private readonly ShoppingCart $shoppingCart)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        return $this->shoppingCart->serialize();
    }
}
