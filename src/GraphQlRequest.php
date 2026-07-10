<?php

declare(strict_types=1);

namespace Magento2GraphQLClient;

final class GraphQlRequest
{
    /**
     * @var array<string, mixed>
     */
    private $variables;

    /**
     * @var array<string, string>
     */
    private $headers;

    /**
     * @param array<string, mixed> $variables
     * @param array<string, string> $headers
     */
    public function __construct(
        string $query,
        array $variables = [],
        ?string $operationName = null,
        array $headers = []
    ) {
        $this->query = $query;
        $this->variables = $variables;
        $this->operationName = $operationName;
        $this->headers = $headers;
    }

    /**
     * @var string
     */
    private $query;

    /**
     * @var string|null
     */
    private $operationName;

    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return array<string, mixed>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getOperationName(): ?string
    {
        return $this->operationName;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        $payload = [
            'query' => $this->query,
            'variables' => $this->variables,
        ];

        if ($this->operationName !== null && $this->operationName !== '') {
            $payload['operationName'] = $this->operationName;
        }
        return $payload;
    }
}
