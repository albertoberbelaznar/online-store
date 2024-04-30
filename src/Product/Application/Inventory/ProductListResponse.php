<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\Inventory;

use Online\Store\Product\Domain\Product;

class ProductListResponse
{
    /**
     * @param Product[] $productList
     */
    public function __construct(private readonly array $productList)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function serialize(): array
    {
        return array_map(static fn (Product $product) => $product->serialize(), $this->productList);
    }
}
