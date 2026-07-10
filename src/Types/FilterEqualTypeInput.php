<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use JsonSerializable;

class FilterEqualTypeInput implements JsonSerializable
{
    /**
     * @var string|array<mixed> $value
     */
    public function __construct(
        protected $value
    ) {
    }

    public static function fromValue($value): self
    {
        return new self($value);
    }


    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $a = [];
        if (isset($this->value)) {
            if(is_array($this->value)) {
                $a['in'] = $this->value;
            } else {
                $a['eq'] = $this->value;
            }
        }
        return $a;
    }

}