<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

trait FilterableFragmentTrait
{
    protected ?FilterInterface $filter = null;

    protected function setFilter(FilterInterface $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    public function hasFilter(): bool
    {
        return $this->filter !== null;
    }

    public function getFilter(): ?FilterInterface
    {
        return $this->filter;
    }
}