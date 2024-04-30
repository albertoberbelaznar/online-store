<?php

declare(strict_types=1);

namespace Online\Store\App\Controller\Product\ShoppingCart;

use Online\Store\Product\Application\Inventory\AddItemCommand;
use Online\Store\Product\Application\Inventory\AddItemToShoppingCartUseCase;
use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddItemToShoppingCartController
{
    private const MANDATORY_PARAMS = ['product_id', 'amount'];

    public function __construct(private readonly AddItemToShoppingCartUseCase $useCase)
    {
    }

    /**
     * @throws InternalErrorException
     */
    public function __invoke(Request $request, string $userId, string $cartId, string $itemId): Response
    {
        $data = $this->getDataFromRequest($request);
        $this->useCase->__invoke(
            new AddItemCommand(
                $userId,
                $cartId,
                $itemId,
                $data['product_id'],
                (float) $data['amount'],
            )
        );

        return new Response();
    }

    /**
     * @throws InternalErrorException
     *
     * @return array<string, mixed>
     */
    private function getDataFromRequest(Request $request): array
    {
        $jsonData = json_decode($request->getContent(), true);
        $errorList = array_filter(self::MANDATORY_PARAMS, static fn ($param) => !isset($jsonData[$param]));
        if ($errorList) {
            throw new InternalErrorException(
                sprintf(
                    'Missing params: %s',
                    implode(',', $errorList)
                )
            );
        }

        return $jsonData;
    }
}
