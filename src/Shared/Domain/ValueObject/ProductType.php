<?php

declare(strict_types=1);

namespace Online\Store\Shared\Domain\ValueObject;

enum ProductType: int
{
    case COUNTABLE = 1;
    case WEIGHTED = 2;
}
