<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class AttributeSelectedOption extends AbstractFragment
{
    public const TYPE_NAME = 'AttributeSelectedOption';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        AttributeSelectedOptionInterface::LABEL => self::SIMPLE_TYPE,
        AttributeSelectedOptionInterface::VALUE => self::SIMPLE_TYPE,
    ];
}