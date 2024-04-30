<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\ShoppingCart;

use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class GetShoppingCartPriceUseCase
{
    public function __construct(
        private readonly ShoppingCart $shoppingCart
    ) {
    }

    /**
     * @throws InternalErrorException
     */
    public function __invoke(string $shoppingCartId): ShoppingCartPriceResponse
    {
        $id = new Uuid($shoppingCartId);
        $price = $this->shoppingCart->calculatePrice($id);

        return new ShoppingCartPriceResponse($id, $price);
    }
}
