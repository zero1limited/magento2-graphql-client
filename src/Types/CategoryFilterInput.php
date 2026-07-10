<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use JsonSerializable;

class CategoryFilterInput implements JsonSerializable
{
    public const CATEGORY_UID = 'category_uid';
    public const IDS = 'ids';
    public const NAME = 'name';
    public const PARENT_CATEGORY_UID = 'parent_category_uid';
    public const PARENT_ID = 'parent_id';
    public const URL_KEY = 'url_key';
    public const URL_PATH = 'url_path';

    public function __construct(
        protected array $filters
    ) {
    }

    public static function fromValue(array $filters): self
    {
        return new self($filters);
    }

    public static function forCategoryUID(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::CATEGORY_UID => $filter,
        ]);
    }

    public function withCategoryUID(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::CATEGORY_UID => $filter,
        ]);
    }

    public static function forCategoryIds(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::IDS => $filter,
        ]);
    }

    public function withCategoryIds(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::IDS => $filter,
        ]);
    }

    public static function forCategoryName(FilterMatchTypeInput $filter): self
    {
        return new self([
            self::NAME => $filter,
        ]);
    }

    public function withCategoryName(FilterMatchTypeInput $filter): self
    {
        return new self([
            self::NAME => $filter,
        ]);
    }

    public static function forParentCategoryUID(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::PARENT_CATEGORY_UID => $filter,
        ]);
    }

    public function withParentCategoryUID(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::PARENT_CATEGORY_UID => $filter,
        ]);
    }

    public static function forParentId(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::PARENT_ID => $filter,
        ]);
    }

    public function withParentId(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::PARENT_ID => $filter,
        ]);
    }

    public static function forUrlKey(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::URL_KEY => $filter,
        ]);
    }

    public function withUrlKey(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::URL_KEY => $filter,
        ]);
    }

    public static function forUrlPath(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::URL_PATH => $filter,
        ]);
    }

    public function withUrlPath(FilterEqualTypeInput $filter): self
    {
        return new self([
            self::URL_PATH => $filter,
        ]);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return $this->filters;
    }
}