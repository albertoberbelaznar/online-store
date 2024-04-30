<?php

declare(strict_types=1);

namespace Online\Store\App\Controller\Product\Inventory;

use Online\Store\Product\Application\Inventory\GetInventoryUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetProductsController
{
    public function __construct(private readonly GetInventoryUseCase $useCase)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $response = $this->useCase->__invoke();

        return new JsonResponse($response->serialize());
    }
}
