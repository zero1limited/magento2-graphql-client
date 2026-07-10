<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

class ProductAttributeFilterInput implements FilterInterface, \JsonSerializable
{
    public const ACTIVITY = 'activity';
    public const CATEGORY_GEAR = 'category_gear';
    public const CATEGORY_ID = 'category_id';
    public const CATEGORY_UID = 'category_uid';
    public const CATEGORY_URL_PATH = 'category_url_path';
    public const CLIMATE = 'climate';
    public const COLLAR = 'collar';
    public const COLOR = 'color';
    public const DESCRIPTION = 'description';
    public const ECO_COLLECTION = 'eco_collection';
    public const ERIN_RECOMMENDS = 'erin_recommends';
    public const FEATURES_BAGS = 'features_bags';
    public const FORMAT = 'format';
    public const GENDER = 'gender';
    public const MATERIAL = 'material';
    public const NAME = 'name';
    public const NEW = 'new';
    public const PATTERN = 'pattern';
    public const PERFORMANCE_FABRIC = 'performance_fabric';
    public const PRICE = 'price';
    public const PURPOSE = 'purpose';
    public const SALE = 'sale';
    public const SHORT_DESCRIPTION = 'short_description';
    public const SIZE = 'size';
    public const SKU = 'sku';
    public const SLEEVE = 'sleeve';
    public const STRAP_BAGS = 'strap_bags';
    public const STYLE_BAGS = 'style_bags';
    public const STYLE_BOTTOM = 'style_bottom';
    public const STYLE_GENERAL = 'style_general';
    public const URL_KEY = 'url_key';

    protected array $filters = [];

    protected array $validFilters = [
        self::ACTIVITY => FilterEqualTypeInput::class,
        self::CATEGORY_GEAR => FilterEqualTypeInput::class,
        self::CATEGORY_ID => FilterEqualTypeInput::class,
        self::CATEGORY_UID => FilterEqualTypeInput::class,
        self::CATEGORY_URL_PATH => FilterEqualTypeInput::class,
        self::CLIMATE => FilterEqualTypeInput::class,
        self::COLLAR => FilterEqualTypeInput::class,
        self::COLOR => FilterEqualTypeInput::class,
        self::DESCRIPTION => FilterEqualTypeInput::class,
        self::ECO_COLLECTION => FilterEqualTypeInput::class,
        self::ERIN_RECOMMENDS => FilterEqualTypeInput::class,
        self::FEATURES_BAGS => FilterEqualTypeInput::class,
        self::FORMAT => FilterEqualTypeInput::class,
        self::GENDER => FilterEqualTypeInput::class,
        self::MATERIAL => FilterEqualTypeInput::class,
        self::NAME => FilterMatchTypeInput::class,
        self::NEW => FilterEqualTypeInput::class,
        self::PATTERN => FilterEqualTypeInput::class,
        self::PERFORMANCE_FABRIC => FilterEqualTypeInput::class,
        self::PRICE => FilterRangeTypeInput::class,
        self::PURPOSE => FilterEqualTypeInput::class,
        self::SALE => FilterEqualTypeInput::class,
        self::SHORT_DESCRIPTION => FilterMatchTypeInput::class,
        self::SIZE => FilterEqualTypeInput::class,
        self::SKU => FilterEqualTypeInput::class,
        self::SLEEVE => FilterEqualTypeInput::class,
        self::STRAP_BAGS => FilterEqualTypeInput::class,
        self::STYLE_BAGS => FilterEqualTypeInput::class,
        self::STYLE_BOTTOM => FilterEqualTypeInput::class,
        self::STYLE_GENERAL => FilterEqualTypeInput::class,
        self::URL_KEY => FilterEqualTypeInput::class,
    ];

    public function __construct(array $filters = [])
    {
        foreach($filters as $key => $value) {
            if (array_key_exists($key, $this->validFilters)) {
                $expectedClass = $this->validFilters[$key];
                if(!is_a($value, $expectedClass)) {
                    throw new \InvalidArgumentException("Invalid filter value for key: $key. Expected instance of $expectedClass.");
                }
                $this->filters[$key] = $value;
            }else{
                throw new \InvalidArgumentException("Invalid filter key: $key, allowed keys are: " . implode(', ', array_keys($this->validFilters)));
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

    public function jsonSerialize(): array
    {
        $serialized = [];
        foreach ($this->filters as $key => $value) {
            if ($value instanceof \JsonSerializable) {
                $serialized[$key] = $value->jsonSerialize();
            } else {
                $serialized[$key] = $value;
            }
        }
        return $serialized;
    }
}