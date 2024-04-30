<?php

declare(strict_types=1);

namespace Online\Store\Tests\Functional\Product\Application\Inventory;

use Online\Store\Product\Application\Inventory\Inventory;
use Online\Store\Product\Domain\Product;
use Online\Store\Product\Domain\ProductRepository;
use Online\Store\Shared\Domain\Exception\InvalidValueException;
use Online\Store\Shared\Domain\ValueObject\Uuid;
use Online\Store\Tests\Fixtures\Product\CountableProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class InventoryTest extends TestCase
{
    private Inventory $inventory;

    /** @var ProductRepository&MockObject */
    private mixed $productRepository;

    public function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->inventory = new Inventory($this->productRepository);
    }

    /**
     * @test
     *
     * @throws InvalidValueException
     */
    public function shouldCallToSaveFunctionOfRepository(): void
    {
        $productId = Uuid::random();
        $product = CountableProductMother::create()
            ->random()
            ->withId($productId)
            ->build();

        $this->productRepository->expects(self::once())
            ->method('save')
            ->with(self::callback(static function (Product $savingProduct) use ($product) {
                self::assertEquals($product->id(), $savingProduct->id());
                self::assertEquals($product->name(), $savingProduct->name());
                self::assertEquals($product->price(), $savingProduct->price());
                self::assertEquals($product->type(), $savingProduct->type());
                self::assertEquals($product->amount(), $savingProduct->amount());
                self::assertNotNull($savingProduct->createdAt());
                self::assertNull($savingProduct->updatedAt());

                return true;
            }));

        $this->inventory->addProduct($productId, $product->serialize());
    }
}
