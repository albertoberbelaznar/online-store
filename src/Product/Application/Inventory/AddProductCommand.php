<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\Inventory;

class AddProductCommand
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly string $id,
        private readonly array $data,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function data(): array
    {
        return $this->data;
    }
}
