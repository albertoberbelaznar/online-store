<?php

declare(strict_types=1);

namespace Online\Store\Product\Application\ShoppingCart;

use Online\Store\Product\Domain\CartItem;
use Online\Store\Product\Domain\Product;
use Online\Store\Product\Domain\ProductRepository;
use Online\Store\Product\Domain\ShoppingCart as ShoppingCartEntity;
use Online\Store\Product\Domain\ShoppingCartRepository;
use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class ShoppingCart
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ShoppingCartRepository $shoppingCartRepository,
    ) {
    }

    /**
     * @throws InvalidValueException
     */
    public function addCartItem(
        Uuid $userId,
        Uuid $cartId,
        Uuid $itemId,
        Uuid $productId,
        float $amount
    ): void {
        $this->validateUnits($productId, $amount);

        $shoppingCart = $this->getShoppingCart($cartId, $userId);
        $product = $this->getProduct($productId);
        $shoppingCart->addItem(
            new CartItem(
                $itemId,
                $cartId,
                $product,
                $amount,
            )
        );

        $this->shoppingCartRepository->save($shoppingCart);
    }

    /**
     * @throws InternalErrorException
     */
    public function getShoppingCartById(Uuid $shoppingCartId): ShoppingCartEntity
    {
        return $this->shoppingCartRepository->getShoppingCartById($shoppingCartId);
    }

    /**
     * @throws InternalErrorException
     */
    public function calculatePrice(Uuid $shoppingCartId): float
    {
        $price = 0;
        $shoppingCart = $this->shoppingCartRepository->getShoppingCartById($shoppingCartId);
        /** @var CartItem $item */
        foreach ($shoppingCart->items()->items() as $item) {
            $price += $item->product()->calculatePrice($item->amount());
        }

        return $price;
    }

    /**
     * @throws InternalErrorException
     */
    private function validateUnits(Uuid $productId, float $amount): void
    {
        $availableAmount = $this->productRepository->getAvailableAmount($productId);

        if ($availableAmount < $amount) {
            throw new InternalErrorException('Out of stock');
        }
    }

    private function getShoppingCart(Uuid $cartId, Uuid $userId): ShoppingCartEntity
    {
        $shoppingCart = $this->shoppingCartRepository->getShoppingCartById($cartId);
        if (!$shoppingCart) {
            $shoppingCart = new ShoppingCartEntity(
                $cartId,
                $userId,
            );
        }

        return $shoppingCart;
    }

    /**
     * @throws InvalidValueException
     */
    private function getProduct(Uuid $productId): Product
    {
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            throw new InvalidValueException('Invalid product id');
        }

        return $product;
    }
}
