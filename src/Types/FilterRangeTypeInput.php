<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use JsonSerializable;

class FilterRangeTypeInput implements JsonSerializable
{
    /**
     * @var string|array<mixed> $value
     */
    public function __construct(
        protected $fromValue,
        protected $toValue
    ) {
    }

    public static function fromValue($fromValue, $toValue): self
    {
        return new self($fromValue, $toValue);
    }


    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $a = [];
        if (isset($this->fromValue)) {
            $a['from'] = $this->fromValue;
        }
        if (isset($this->toValue)) {
            $a['to'] = $this->toValue;
        }
        return $a;
    }

}