<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\Inventory;

use Online\Store\Product\Application\ShoppingCart\ShoppingCart;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class AddItemToShoppingCartUseCase
{
    public function __construct(private readonly ShoppingCart $shoppingCart)
    {
    }

    /**
     * @throws InvalidValueException
     */
    public function __invoke(AddItemCommand $command): void
    {
        $this->shoppingCart->addCartItem(
            new Uuid($command->userId()),
            new Uuid($command->cartId()),
            new Uuid($command->itemId()),
            new Uuid($command->productId()),
            $command->amount(),
        );
    }
}
