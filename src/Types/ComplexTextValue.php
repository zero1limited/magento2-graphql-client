<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class ComplexTextValue extends AbstractFragment
{
    public const HTML = 'html';
    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::HTML => self::SIMPLE_TYPE
    ];
}