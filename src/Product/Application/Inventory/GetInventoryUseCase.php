<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\Inventory;

use Online\Store\Shared\Domain\Exception\InternalErrorException;

class GetInventoryUseCase
{
    public function __construct(
        private readonly Inventory $inventory
    ) {
    }

    /**
     * @throws InternalErrorException
     */
    public function __invoke(): ProductListResponse
    {
        $products = $this->inventory->getAllProducts();

        return new ProductListResponse($products->items());
    }
}
