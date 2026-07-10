<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

class CMSBlock
{
    public const CONTENT = 'content';
    public const IDENTIFIER = 'identifier';
    public const TITLE = 'title';

    public function __construct(
        protected array $attributes = []
    ) {
    }

    public static function withAttributes(array $attributes): self
    {
        return new self($attributes);
    }

    public function __toString(): string
    {
        return implode(' ', $this->attributes);
    }
}