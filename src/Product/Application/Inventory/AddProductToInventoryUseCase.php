<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\Inventory;

use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class AddProductToInventoryUseCase
{
    public function __construct(private readonly Inventory $inventory)
    {
    }

    /**
     * @throws InternalErrorException
     * @throws InvalidValueException
     */
    public function __invoke(AddProductCommand $command): void
    {
        $this->inventory->addProduct(
            new Uuid($command->id()),
            $command->data(),
        );
    }
}
