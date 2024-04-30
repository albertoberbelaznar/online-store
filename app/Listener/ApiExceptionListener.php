<?php

declare(strict_types=1);

namespace Online\Store\App\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ApiExceptionListener
{
    public function __construct(private readonly ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $event->setResponse(
            new JsonResponse(
                [
                    'code' => $this->exceptionCodeFor($exception),
                    'message' => $exception->getMessage(),
                ],
                $this->exceptionHandler->statusCodeFor(\get_class($exception))
            )
        );
    }

    private function exceptionCodeFor(Throwable $error): string
    {
        return \get_class($error);
    }
}
