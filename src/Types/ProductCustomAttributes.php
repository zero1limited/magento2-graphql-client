<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;
use Magento2GraphQLClient\Types\FilterableFragmentTrait;
use Magento2GraphQLClient\Types\FilterableFragmentInterface;

class ProductCustomAttributes extends AbstractFragment implements FilterableFragmentInterface
{
    use FilterableFragmentTrait;

    public const ERRORS = 'errors';
    public const ITEMS = 'items';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        self::ERRORS => AttributeMetadataError::class,
        self::ITEMS => AttributeValueInterface::class,
    ];

    public function withFilter(FilterInterface $filter): self
    {
        if($filter instanceof AttributeFilterInput) {
            return $this->setFilter($filter);
        } else {
            throw new \InvalidArgumentException('Filter must be an instance of AttributeFilterInput');
        }
    }
}