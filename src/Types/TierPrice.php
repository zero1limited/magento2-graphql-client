<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class TierPrice extends AbstractFragment
{
    public const DISCOUNT = 'discount';
    public const FINAL_PRICE = 'final_price';
    public const QUANTITY = 'quantity';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::DISCOUNT => ProductDiscount::class,
        self::FINAL_PRICE => Money::class,
        self::QUANTITY => self::SIMPLE_TYPE,
    ];
}