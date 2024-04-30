<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\ValueObject\Uuid;

interface ShoppingCartRepository
{
    /**
     * @throws InternalErrorException
     */
    public function getShoppingCartById(Uuid $cartId): ?ShoppingCart;

    /**
     * @throws InternalErrorException
     */
    public function save(ShoppingCart $shoppingCart): void;
}
