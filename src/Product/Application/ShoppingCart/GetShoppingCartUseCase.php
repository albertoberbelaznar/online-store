<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\ShoppingCart;

use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class GetShoppingCartUseCase
{
    public function __construct(
        private readonly ShoppingCart $shoppingCart
    ) {
    }

    /**
     * @throws InternalErrorException
     */
    public function __invoke(string $shoppingCartId): ShoppingCartResponse
    {
        $shoppingCart = $this->shoppingCart->getShoppingCartById(new Uuid($shoppingCartId));

        return new ShoppingCartResponse($shoppingCart);
    }
}
