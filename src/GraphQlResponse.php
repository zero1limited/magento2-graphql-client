<?php

declare(strict_types=1);

namespace Magento2GraphQLClient;

final class GraphQlResponse
{
    /**
     * @var array<string, mixed>
     */
    private $raw;

    /**
     * @param array<string, mixed> $raw
     */
    public function __construct(array $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return isset($this->raw['data']) && is_array($this->raw['data'])
            ? $this->raw['data']
            : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getErrors(): array
    {
        return isset($this->raw['errors']) && is_array($this->raw['errors'])
            ? $this->raw['errors']
            : [];
    }

    public function hasErrors(): bool
    {
        return $this->getErrors() !== [];
    }

    /**
     * @return mixed|null
     */
    public function path(string $path)
    {
        if ($path === '') {
            return null;
        }

        $parts = explode('.', $path);
        $cursor = $this->raw;

        foreach ($parts as $part) {
            if (!is_array($cursor) || !array_key_exists($part, $cursor)) {
                return null;
            }

            $cursor = $cursor[$part];
        }

        return $cursor;
    }
}
