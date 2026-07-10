<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

class AttributeFilterInput implements FilterInterface
{
    public const IS_COMPARABLE = 'is_comparable';
    public const IS_FILTERABLE = 'is_filterable';
    public const IS_FILTERABLE_IN_SEARCH = 'is_filterable_in_search';
    public const IS_HTML_ALLOWED_ON_FRONT = 'is_html_allowed_on_front';
    public const IS_SEARCHABLE = 'is_searchable';
    public const IS_USED_FOR_PRICE_RULES = 'is_used_for_price_rules';
    public const IS_USED_FOR_PROMO_RULES = 'is_used_for_promo_rules';
    public const IS_VISIBLE_IN_ADVANCED_SEARCH = 'is_visible_in_advanced_search';
    public const IS_VISIBLE_ON_FRONT = 'is_visible_on_front';
    public const IS_WYSIWYG_ENABLED = 'is_wysiwyg_enabled';
    public const USED_IN_PRODUCT_LISTING = 'used_in_product_listing';

    protected array $filters = [
        self::IS_COMPARABLE => false,
        self::IS_FILTERABLE => false,
        self::IS_FILTERABLE_IN_SEARCH => false,
        self::IS_HTML_ALLOWED_ON_FRONT => false,
        self::IS_SEARCHABLE => false,
        self::IS_USED_FOR_PRICE_RULES => false,
        self::IS_USED_FOR_PROMO_RULES => false,
        self::IS_VISIBLE_IN_ADVANCED_SEARCH => false,
        self::IS_VISIBLE_ON_FRONT => false,
        self::IS_WYSIWYG_ENABLED => false,
        self::USED_IN_PRODUCT_LISTING => false,
    ];

    public function __construct(array $filters = [])
    {
        foreach($filters as $key => $value) {
            if (array_key_exists($key, $this->filters)) {
                $this->filters[$key] = $value;
            }else{
                throw new \InvalidArgumentException("Invalid filter key: $key");
            }
        }
    }

    public static function create($filters): self
    {
        return new self($filters);
    }

    public function __toString(): string
    {
        $fs = [];
        foreach($this->filters as $key => $value) {
            if($value){
                $fs[] = $key.': true';
            }
        }
        return implode(', ', $fs);
    }
}