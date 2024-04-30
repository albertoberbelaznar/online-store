<?php

declare(strict_types=1);

namespace Online\Store\App\Controller\Product\ShoppingCart;

use Online\Store\Product\Application\ShoppingCart\GetShoppingCartUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetShoppingCartController
{
    public function __construct(private readonly GetShoppingCartUseCase $useCase)
    {
    }

    public function __invoke(Request $request, string $userId, string $cartId): JsonResponse
    {
        $response = $this->useCase->__invoke($cartId);

        return new JsonResponse($response->serialize());
    }
}
