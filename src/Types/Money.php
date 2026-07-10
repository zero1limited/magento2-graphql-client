<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class Money extends AbstractFragment
{
    public const CURRENCY = 'currency';
    public const VALUE = 'value';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::CURRENCY => self::SIMPLE_TYPE,
        self::VALUE => self::SIMPLE_TYPE,
    ];
}