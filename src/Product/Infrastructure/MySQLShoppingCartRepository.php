<?php

declare(strict_types=1);

namespace Online\Store\Product\Infrastructure;

use Doctrine\DBAL\Connection;
use Online\Store\Product\Domain\CartItem;
use Online\Store\Product\Domain\CartItemCollection;
use Online\Store\Product\Domain\Product;
use Online\Store\Product\Domain\ProductFactory;
use Online\Store\Product\Domain\ShoppingCart;
use Online\Store\Product\Domain\ShoppingCartRepository;
use Online\Store\Shared\Domain\Exception\InternalErrorException;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\ProductType;
use Online\Store\Shared\Domain\ValueObject\Uuid;

class MySQLShoppingCartRepository implements ShoppingCartRepository
{
    private const TABLE_CART = 'shopping_cart';
    private const TABLE_CART_ITEM = 'cart_item';
    private const TABLE_PRODUCT = 'product';

    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws InternalErrorException
     */
    public function getShoppingCartById(Uuid $cartId): ?ShoppingCart
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->select('*')
                ->from(self::TABLE_CART)
                ->where('id = :id')
                ->setParameter('id', $cartId->value())
                ->fetchAssociative();

            if (!$result) {
                return null;
            }

            return $this->fromDataToShoppingCart($result);
        } catch (\Throwable $e) {
            throw new InternalErrorException('Query error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    public function save(ShoppingCart $shoppingCart): void
    {
        $exists = $this->getShoppingCartById($shoppingCart->id());
        if (!$exists) {
            $this->insertShoppingCart($shoppingCart);
        } else {
            $this->updateShoppingCart($shoppingCart);
        }

        $this->saveCartItems($shoppingCart->items());
    }

    /**
     * @param array<string, mixed> $cartData
     *
     * @throws InternalErrorException
     * @throws InvalidValueException
     */
    private function fromDataToShoppingCart(array $cartData): ShoppingCart
    {
        $id = new Uuid($cartData['id']);

        return new ShoppingCart(
            $id,
            new Uuid($cartData['user_id']),
            new Date($cartData['created_at']),
            isset($cartData['updated_at']) ? new Date($cartData['updated_at']) : null,
            $this->getCartItems($id)
        );
    }

    /**
     * @throws InternalErrorException
     */
    private function getCartItems(Uuid $shoppingCartId): CartItemCollection
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->select('*')
                ->from(self::TABLE_CART_ITEM)
                ->where('shopping_cart_id = :id')
                ->setParameter('id', $shoppingCartId->value())
                ->fetchAllAssociative();

            if (empty($result)) {
                return new CartItemCollection([]);
            }

            $items = [];
            foreach ($result as $itemData) {
                $items[] = new CartItem(
                    new Uuid($itemData['id']),
                    new Uuid($itemData['shopping_cart_id']),
                    $this->getProduct(new Uuid($itemData['product_id'])),
                    (float) $itemData['amount'],
                    new Date($itemData['created_at']),
                    isset($itemData['updated_at']) ? new Date($itemData['updated_at']) : null
                );
            }

            return new CartItemCollection($items);
        } catch (\Throwable $e) {
            throw new InternalErrorException('Query error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function getProduct(Uuid $productId): Product
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->select('*')
                ->from(self::TABLE_PRODUCT)
                ->where('id = :id')
                ->setParameter('id', $productId->value())
                ->fetchAssociative();

            if (!$result) {
                throw new InvalidValueException('Invalid product id');
            }

            return ProductFactory::build(ProductType::from((int) $result['type']))->getProduct(
                new Uuid($result['id']),
                $result
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException('Query error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function insertShoppingCart(ShoppingCart $shoppingCart): void
    {
        try {
            $this->connection->insert(
                self::TABLE_CART,
                [
                    'id' => $shoppingCart->id()->value(),
                    'user_id' => $shoppingCart->userId(),
                    'created_at' => $shoppingCart->createdAt()->stringDateTime(),
                    'updated_at' => $shoppingCart->updatedAt()?->stringDateTime(),
                ]
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException('Insert error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function updateShoppingCart(ShoppingCart $shoppingCart): void
    {
        try {
            $this->connection->update(
                self::TABLE_CART,
                [
                    'updated_at' => (new Date())->stringDateTime(),
                ],
                [
                    'id' => $shoppingCart->id()->value(),
                ]
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException('Insert error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function saveCartItems(CartItemCollection $items): void
    {
        foreach ($items->items() as $item) {
            $exists = $this->existsCartItem($item->id());
            if (!$exists) {
                $this->insertCartItem($item);
            } else {
                $this->updateCartItem($item);
            }
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function existsCartItem(Uuid $cartItemId): bool
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->select('*')
                ->from(self::TABLE_CART_ITEM)
                ->where('id = :id')
                ->setParameter('id', $cartItemId->value())
                ->fetchAssociative();

            if (!$result) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            throw new InternalErrorException('Query error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function insertCartItem(CartItem $item): void
    {
        try {
            $this->connection->insert(
                self::TABLE_CART_ITEM,
                [
                    'id' => $item->id()->value(),
                    'shopping_cart_id' => $item->shoppingCartId()->value(),
                    'product_id' => $item->product()->id()->value(),
                    'amount' => $item->amount(),
                    'created_at' => $item->createdAt()->stringDateTime(),
                ]
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException('Insert error: ' . $e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     */
    private function updateCartItem(CartItem $item): void
    {
        try {
            $this->connection->update(
                self::TABLE_CART_ITEM,
                [
                    'product_id' => $item->product()->id()->value(),
                    'amount' => $item->amount(),
                    'updated_at' => (new Date())->stringDateTime(),
                ],
                [
                    'id' => $item->id()->value(),
                ]
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException('Insert error: ' . $e->getMessage());
        }
    }
}
