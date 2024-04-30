<?php

declare(strict_types=1);

namespace Online\Store\Shared\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Online\Store\Shared\Domain\Exception\InvalidValueException;

class Date
{
    private const TIMEZONE = 'UTC';

    private const DATABASE_TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    private DateTimeImmutable $date;

    /** @throws InvalidValueException */
    public function __construct(?string $date = null)
    {
        try {
            $this->date = $date ?
                new DateTimeImmutable($date) :
                new DateTimeImmutable('now', new DateTimeZone(self::TIMEZONE));
        } catch (Exception) {
            throw new InvalidValueException("Invalid date value {$date}");
        }
    }

    public function __toString(): string
    {
        return $this->stringDateTime();
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function stringDateTime(): string
    {
        return $this->date
            ->format(self::DATABASE_TIMESTAMP_FORMAT);
    }
}
