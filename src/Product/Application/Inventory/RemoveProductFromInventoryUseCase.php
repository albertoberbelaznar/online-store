<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\Inventory;

use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class RemoveProductFromInventoryUseCase
{
    public function __construct(private readonly Inventory $inventory)
    {
    }

    /**
     * @throws InternalErrorException
     */
    public function __invoke(Uuid $productId): void
    {
        $this->inventory->removeProduct($productId);
    }
}
