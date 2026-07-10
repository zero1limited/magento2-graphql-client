<?php

declare(strict_types=1);

namespace Magento2GraphQLClient;

use Magento2GraphQLClient\AdobeCommerceClient\Categories;
use Magento2GraphQLClient\AdobeCommerceClient\Products;
use Magento2GraphQLClient\Operations\AdobeCommerceOperations;

class AdobeCommerceClient
{
    /**
     * @var GraphQlClient
     */
    private $client;

    protected Categories $categories;
    protected Products $products;

    public function __construct(GraphQlClient $client)
    {
        $this->client = $client;
        $this->categories = new Categories($client);
        $this->products = new Products($client);
    }

    /**
     * @return array<string, mixed>
     */
    public function availableStores(bool $useCurrentGroup = false): array
    {
        return $this->client
            ->query(
                AdobeCommerceOperations::AVAILABLE_STORES,
                ['useCurrentGroup' => $useCurrentGroup],
                'availableStores'
            )
            ->getData();
    }

    /**
     * @return array<string, mixed>
     */
    public function storeConfig(): array
    {
        return $this->client
            ->query(AdobeCommerceOperations::STORE_CONFIG, [], 'storeConfig')
            ->getData();
    }

    public function __call(string $method, array $args) {
        if(is_callable($this->$method)) {
            return call_user_func_array($this->$method, $args);
        }
        throw new \BadMethodCallException("Method $method does not exist");
    }

    /**
     * @param array<string, mixed> $variables
     * @return array<string, mixed>
     */
    public function cmsPage(array $variables = []): array
    {
        return $this->client
            ->query(AdobeCommerceOperations::CMS_PAGE, $variables, 'cmsPage')
            ->getData();
    }

    /**
     * @param array<string, mixed> $variables
     * @return array<string, mixed>
     */
    public function cmsBlocks(array $variables = []): array
    {
        return $this->client
            ->query(AdobeCommerceOperations::CMS_BLOCKS, $variables, 'cmsBlocks')
            ->getData();
    }

    /**
     * @return array<string, mixed>
     */
    public function cart(string $cartId): array
    {
        return $this->client
            ->query(AdobeCommerceOperations::CART, ['cart_id' => $cartId], 'cart')
            ->getData();
    }

    /**
     * @return array<string, mixed>
     */
    public function customerCart(): array
    {
        return $this->client
            ->query(AdobeCommerceOperations::CUSTOMER_CART, [], 'customerCart')
            ->getData();
    }

    /**
     * @return array<string, mixed>
     */
    public function customer(): array
    {
        return $this->client
            ->query(AdobeCommerceOperations::CUSTOMER, [], 'customer')
            ->getData();
    }

    /**
     * @return array<string, mixed>
     */
    public function route(string $url): array
    {
        return $this->client
            ->query(AdobeCommerceOperations::ROUTE, ['url' => $url], 'route')
            ->getData();
    }

    public function generateCustomerToken(string $email, string $password): string
    {
        $data = $this->client
            ->mutation(
                AdobeCommerceOperations::GENERATE_CUSTOMER_TOKEN,
                ['email' => $email, 'password' => $password],
                'generateCustomerToken'
            )
            ->getData();

        return (string) ($data['generateCustomerToken']['token'] ?? '');
    }

    public function createEmptyCart(): string
    {
        $data = $this->client
            ->mutation(AdobeCommerceOperations::CREATE_EMPTY_CART, [], 'createEmptyCart')
            ->getData();

        return (string) ($data['createEmptyCart'] ?? '');
    }

    public function createCustomerCart(): string
    {
        $data = $this->client
            ->mutation(AdobeCommerceOperations::CREATE_CUSTOMER_CART, [], 'createCustomerCart')
            ->getData();

        return (string) ($data['createCustomerCart'] ?? '');
    }

    public function rawClient(): GraphQlClient
    {
        return $this->client;
    }
}
