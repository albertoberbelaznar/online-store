<?php

declare(strict_types=1);

namespace Online\Store\Shared\Domain\Exception;

final class MissingParamException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
