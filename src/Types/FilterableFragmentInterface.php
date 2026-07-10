<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

interface FilterableFragmentInterface
{
    public function withFilter(FilterInterface $filter): self;

    public function hasFilter(): bool;

    public function getFilter(): ?FilterInterface;
}