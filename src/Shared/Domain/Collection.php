<?php

declare(strict_types=1);

namespace Online\Store\Shared\Domain;

use ArrayIterator;
use Countable;
use Exception;
use IteratorAggregate;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @implements IteratorAggregate<TKey, TValue>
 */
abstract class Collection implements Countable, IteratorAggregate
{
    /** @param array<int, mixed> $items */
    public function __construct(private array $items = [])
    {
        Assert::arrayOf($this->type(), $items);
    }

    /** @return ArrayIterator<int, TValue> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items());
    }

    public function count(): int
    {
        return \count($this->items());
    }

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    /** @throws Exception */
    public function remove(mixed $itemToRemove): void
    {
        foreach ($this->getIterator() as $key => $item) {
            if ($item === $itemToRemove) {
                unset($this->items[$key]);
            }
        }
    }

    /** @return array<TValue>> */
    public function items(): array
    {
        return $this->items;
    }

    abstract protected function type(): string;
}
