<?php

declare(strict_types=1);

namespace Online\Store\App\Listener;

use InvalidArgumentException;
use Online\Store\Shared\Domain\Exception\MissingParamException;
use function Lambdish\Phunctional\get;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Symfony\Component\HttpFoundation\Response;

final class ApiExceptionsHttpStatusCodeMapping
{
    private const DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    /** @var array<string, int> */
    private array $exceptions = [
        InvalidValueException::class => Response::HTTP_BAD_REQUEST,
        MissingParamException::class => Response::HTTP_BAD_REQUEST,
    ];

    public function register(string $exceptionClass, int $statusCode): void
    {
        $this->exceptions[$exceptionClass] = $statusCode;
    }

    public function statusCodeFor(string $exceptionClass): int
    {
        $statusCode = get($exceptionClass, $this->exceptions, self::DEFAULT_STATUS_CODE);

        if (null === $statusCode) {
            throw new InvalidArgumentException("There are no status code mapping for <{$exceptionClass}>");
        }

        return $statusCode;
    }
}
