<?php

declare(strict_types=1);

namespace Online\Store\Product\Domain;

interface PricingStrategy
{
    public function calculatePrice(float $amount): float;
}
