<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class AttributeSelectedOptionsInterface extends AbstractFragment
{
    public const TYPE_NAME = 'AttributeSelectedOptions';

    public const SELECTED_OPTIONS = 'selected_options';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::SELECTED_OPTIONS => AttributeSelectedOptionInterface::class,
    ];
}