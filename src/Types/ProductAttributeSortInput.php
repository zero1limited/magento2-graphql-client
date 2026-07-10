<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

class ProductAttributeSortInput implements FilterInterface, \JsonSerializable
{
    public const SORT_ASC = 'ASC';
    public const SORT_DESC = 'DESC';

    protected array $sorts = [];

    public function __construct(array $sorts = [])
    {
        foreach($sorts as $key => $value) {
            if (!in_array($value, [self::SORT_ASC, self::SORT_DESC], true)) {
                throw new \InvalidArgumentException("Invalid sort value for key: $key. Expected 'ASC' or 'DESC'.");
            }
            $this->sorts[$key] = $value;
        }
    }

    public static function create($filters): self
    {
        return new self($filters);
    }

    public static function createWithSort(string $attribute, string $direction): self
    {
        return new self([$attribute => $direction]);
    }

    public function __toString(): string
    {
        $fs = [];
        foreach($this->sorts as $key => $value) {
            if($value){
                $fs[] = $key.': '.$value;
            }
        }
        return implode(', ', $fs);
    }

    public function jsonSerialize(): array
    {
        return $this->sorts;
    }
}