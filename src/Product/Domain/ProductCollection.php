<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

use Online\Store\Shared\Domain\Collection;

/** @extends Collection<int, Product> */
final class ProductCollection extends Collection
{
    protected function type(): string
    {
        return Product::class;
    }
}
