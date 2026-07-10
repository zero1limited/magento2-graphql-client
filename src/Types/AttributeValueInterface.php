<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class AttributeValueInterface extends AbstractFragment
{
    public const CODE = 'code';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::CODE => self::SIMPLE_TYPE,
    ];
}