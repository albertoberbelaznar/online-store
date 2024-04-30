<?php

declare(strict_types=1);

namespace Online\Store\Tests\Fixtures\Product;

use Online\Store\Product\Domain\CountableProduct;
use Online\Store\Shared\Domain\ValueObject\Date;
use Online\Store\Shared\Domain\ValueObject\Name;
use Online\Store\Shared\Domain\ValueObject\Uuid;
use Online\Store\Tests\Fixtures\Mother;

class CountableProductMother extends Mother
{
    private Uuid $id;
    private Name $name;
    private float $price;
    private float $amount;
    private ?Date $createdAt;
    private ?Date $updatedAt;

    public static function create(): self
    {
        return new self();
    }

    public function random(): CountableProductMother
    {
        $this->id = Uuid::random();
        $this->name = new Name($this->faker->name());
        $this->price = $this->faker->randomFloat(2);
        $this->amount = $this->faker->randomFloat(0);
        $this->createdAt = new Date();
        $this->updatedAt = null;

        return $this;
    }

    public function build(): CountableProduct
    {
        return new CountableProduct(
            $this->id,
            $this->name,
            $this->price,
            $this->amount,
            $this->createdAt,
            $this->updatedAt,
        );
    }

    public function withId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }
}
