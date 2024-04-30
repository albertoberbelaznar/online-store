<?php

declare(strict_types=1);

namespace Online\Store\App\Controller\Product\Inventory;

use Online\Store\Product\Application\Inventory\RemoveProductFromInventoryUseCase;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveProductFromInventoryController
{
    public function __construct(private readonly RemoveProductFromInventoryUseCase $useCase)
    {
    }

    /**
     * @throws InvalidValueException
     */
    public function __invoke(Request $request, string $id): Response
    {
        $this->useCase->__invoke(new Uuid($id));

        return new Response();
    }
}
