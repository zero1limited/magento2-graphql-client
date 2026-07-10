<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class ProductDiscount extends AbstractFragment
{
    public const AMOUNT_OFF = 'amount_off';
    public const PERCENT_OFF = 'percent_off';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::AMOUNT_OFF => self::SIMPLE_TYPE,
        self::PERCENT_OFF => self::SIMPLE_TYPE,
    ];
}