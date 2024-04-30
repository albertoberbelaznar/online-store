<?php

declare(strict_types=1);

namespace Online\Store\App\Controller\Product\Inventory;

use Online\Store\Product\Application\Inventory\AddProductCommand;
use Online\Store\Product\Application\Inventory\AddProductToInventoryUseCase;
use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddProductToInventoryController
{
    private const MANDATORY_PARAMS = ['name', 'price', 'type', 'amount'];

    public function __construct(private readonly AddProductToInventoryUseCase $useCase)
    {
    }

    /**
     * @throws InternalErrorException
     */
    public function __invoke(Request $request, string $id): Response
    {
        $data = $this->getDataFromRequest($request);
        $this->useCase->__invoke(
            new AddProductCommand(
                $id,
                $data,
            )
        );

        return new Response();
    }

    /**
     * @throws InternalErrorException
     *
     * @return array<string, string>
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
