<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class AttributeSelectedOptionInterface extends AbstractFragment
{
    public const LABEL = 'label';
    public const VALUE = 'value';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::LABEL => self::SIMPLE_TYPE,
        self::VALUE => self::SIMPLE_TYPE,
    ];
}