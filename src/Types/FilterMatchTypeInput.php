<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use JsonSerializable;

class FilterMatchTypeInput implements JsonSerializable
{
    public function __construct(
        protected string $value,
        protected FilterMatchTypeEnum $matchType
    ) {
    }

    public static function fromValue(string $value, FilterMatchTypeEnum $matchType): self
    {
        return new self($value, $matchType);
    }


    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $a = [];
        $a['value'] = $this->value;
        $a['match_type'] = $this->matchType->value;
        return $a;
    }
}