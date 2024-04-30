<?php

declare(strict_types=1);

namespace Online\Store\Shared\Domain\ValueObject;

use Online\Store\Shared\Domain\Exception\InvalidValueException;

class Name
{
    /**
     * @throws InvalidValueException
     */
    public function __construct(private readonly string $value)
    {
        $this->validate($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @throws InvalidValueException
     */
    private function validate(string $value): void
    {
        if ($value === '') {
            throw new InvalidValueException('Name must be populated');
        }
    }
}
