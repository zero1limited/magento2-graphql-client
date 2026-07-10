<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class MediaGalleryInterface extends AbstractFragment
{
    public const DISABLED = 'disabled';
    public const LABEL = 'label';
    public const POSITION = 'position';
    public const URL = 'url';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::__TYPENAME => self::SIMPLE_TYPE,
        self::DISABLED => self::SIMPLE_TYPE,
        self::LABEL => self::SIMPLE_TYPE,
        self::POSITION => self::SIMPLE_TYPE,
        self::URL => self::SIMPLE_TYPE
    ];
}