<?php

declare(strict_types=1);

namespace Magento2GraphQLClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Magento2GraphQLClient\Exception\GraphQlClientException;
use Magento2GraphQLClient\Exception\GraphQlResponseException;
use Magento2GraphQLClient\Exception\GraphQlTransportException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class GraphQlClient
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var array<string, string>
     */
    private $defaultHeaders;

    protected LoggerInterface $logger;

    /**
     * @param array<string, string> $defaultHeaders
     * @param array<string, mixed> $httpOptions
     */
    public function __construct(
        string $endpoint,
        array $defaultHeaders = [],
        ?ClientInterface $httpClient = null,
        array $httpOptions = [],
        ?LoggerInterface $logger = null
    ) {
        $this->endpoint = $endpoint;
        $this->defaultHeaders = $defaultHeaders;

        $this->httpClient = $httpClient ?? new HttpClient($httpOptions);

        if(!$logger){
            $this->logger = new NullLogger();
        }else{
            $this->logger = $logger;
        }
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, string> $headers
     */
    public function query(
        string $query,
        array $variables = [],
        ?string $operationName = null,
        array $headers = []
    ): GraphQlResponse {
        $request = new GraphQlRequest($query, $variables, $operationName, $headers);

        return $this->execute($request);
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, string> $headers
     */
    public function mutation(
        string $mutation,
        array $variables = [],
        ?string $operationName = null,
        array $headers = []
    ): GraphQlResponse {
        $request = new GraphQlRequest($mutation, $variables, $operationName, $headers);

        return $this->execute($request);
    }

    public function execute(GraphQlRequest $request): GraphQlResponse
    {
        try {
            $this->logger->debug('Sending GraphQL request', [
                'request' => [
                    'endpoint' => $this->endpoint,
                    'headers' => $this->buildHeaders($request),
                    'query' => $request->toPayload(),
                ]
            ]);
            $httpResponse = $this->httpClient->request('POST', $this->endpoint, [
                'headers' => $this->buildHeaders($request),
                'json' => $request->toPayload(),
            ]);
            $this->logger->debug('Got GraphQL response', [
                'request' => [
                    'endpoint' => $this->endpoint,
                    'headers' => $this->buildHeaders($request),
                    'query' => $request->toPayload(),
                ],
                'response' => [
                    'body' => (string) $httpResponse->getBody(),
                    'status_code' => $httpResponse->getStatusCode(),
                ]
            ]);
        } catch (GuzzleException $exception) {
            throw GraphQlTransportException::fromThrowable($exception);
        }

        $body = (string) $httpResponse->getBody();
        if ($body === '') {
            throw new GraphQlClientException('GraphQL endpoint returned an empty response body');
        }

        $decoded = json_decode($body, true);

        if (!is_array($decoded)) {
            $this->logger->error('GraphQL response contains errors', [
                'request' => [
                    'headers' => $this->buildHeaders($request),
                    'query' => $request->toPayload(),
                ],
                'response' => [
                    'body' => $body,
                    'status_code' => $httpResponse->getStatusCode(),
                ]
            ]);
            throw new GraphQlClientException('GraphQL endpoint returned invalid JSON');
        }

        $response = new GraphQlResponse($decoded);

        if ($response->hasErrors()) {
            $this->logger->error('GraphQL response contains errors', [
                'request' => [
                    'headers' => $this->buildHeaders($request),
                    'query' => $request->toPayload(),
                ],
                'response' => [
                    'body' => $decoded,
                    'status_code' => $httpResponse->getStatusCode(),
                ]
            ]);
            throw new GraphQlResponseException($response->getErrors());
        }

        return $response;
    }

    /**
     * @param array<string, string> $defaultHeaders
     */
    public function withDefaultHeaders(array $defaultHeaders): self
    {
        $clone = clone $this;
        $clone->defaultHeaders = $defaultHeaders;

        return $clone;
    }

    public function withBearerToken(string $token): self
    {
        $headers = $this->defaultHeaders;
        $headers['Authorization'] = 'Bearer ' . $token;

        return $this->withDefaultHeaders($headers);
    }

    public function withStore(string $storeCode): self
    {
        $headers = $this->defaultHeaders;
        $headers['Store'] = $storeCode;

        return $this->withDefaultHeaders($headers);
    }

    /**
     * @return array<string, string>
     */
    public function getDefaultHeaders(): array
    {
        return $this->defaultHeaders;
    }

    /**
     * @return array<string, string>
     */
    private function buildHeaders(GraphQlRequest $request): array
    {
        return array_merge([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $this->defaultHeaders, $request->getHeaders());
    }
}
