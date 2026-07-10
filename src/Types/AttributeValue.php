<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class AttributeValue extends AbstractFragment
{
    public const TYPE_NAME = 'AttributeValue';
    public const CODE = 'code';
    public const VALUE = 'value';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::CODE => self::SIMPLE_TYPE,
        self::VALUE => self::SIMPLE_TYPE,
    ];
}