<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class AttributeMetadataError extends AbstractFragment
{
    public const TYPE_NAME = '__typename';
    public const MESSAGE = 'message';
    public const TYPE = 'type';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::MESSAGE => self::SIMPLE_TYPE,
        self::TYPE => self::SIMPLE_TYPE,
    ];
}