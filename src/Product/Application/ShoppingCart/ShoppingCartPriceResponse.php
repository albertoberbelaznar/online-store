<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\ShoppingCart;

use Online\Store\Shared\Domain\ValueObject\Uuid;

class ShoppingCartPriceResponse
{
    public function __construct(
        private readonly Uuid $shoppingCartId,
        private readonly float $price
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        return [
            'shopping_cart_id' => $this->shoppingCartId->value(),
            'price' => $this->price,
        ];
    }
}
