# Magento 2 GraphQL Client (Adobe Commerce 2.4.8)

Generated GraphQL client library targeting the Adobe Commerce GraphQL reference for version 2.4.8:
https://developer.adobe.com/commerce/webapi/reference/graphql/2-4-8

This package provides:

- A reusable low-level GraphQL HTTP client based on Guzzle
- Strong request/response primitives
- Typed exceptions for transport and GraphQL response errors
- A high-level Adobe Commerce client with common query/mutation helpers
- A curated operation set aligned to the 2.4.8 reference

**STILL VERY MUCH A WIP and POC**  

## Installation

```bash
composer require zero1/magento2-graphql-client
```

## Adobe Commerce endpoint

Adobe Commerce GraphQL endpoint format:

```text
https://<your-store>/graphql
```

## Quick start

```php
<?php

declare(strict_types=1);

use Magento2GraphQLClient\AdobeCommerceClient;
use Magento2GraphQLClient\GraphQlClient;

require_once __DIR__ . '/vendor/autoload.php';

$client = new GraphQlClient('https://example.com/graphql');
$commerce = new AdobeCommerceClient($client);

$storeConfig = $commerce->storeConfig();

var_dump($storeConfig['storeConfig']['store_name'] ?? null);
```

## Authentication and headers

Use bearer tokens and store headers through the low-level client:

```php
<?php

$baseClient = new GraphQlClient('https://example.com/graphql');

$customerClient = $baseClient
	->withBearerToken('customer-or-admin-token')
	->withStore('default');
```

Notes:

- `Authorization: Bearer <token>` is supported by `withBearerToken()`
- `Store: <store_code>` is supported by `withStore()`
- Additional GraphQL headers can be passed per request

## High-level AdobeCommerce client

The `AdobeCommerceClient` contains common methods mapped to 2.4.8 operations:

- `availableStores()`
- `storeConfig()`
- `products()`
- `categories()`
- `cmsPage()`
- `cmsBlocks()`
- `cart()`
- `customerCart()`
- `customer()`
- `route()`
- `generateCustomerToken()`
- `createEmptyCart()`
- `createCustomerCart()`

Example:

```php
<?php

$data = $commerce->products([
	'search' => 'hoodie',
	'pageSize' => 12,
	'currentPage' => 1,
]);

foreach (($data['products']['items'] ?? []) as $item) {
	echo ($item['sku'] ?? '') . PHP_EOL;
}
```

## Low-level GraphQL client

Use `GraphQlClient` when you want full control over operation text and headers.

```php
<?php

use Magento2GraphQLClient\GraphQlClient;

$client = new GraphQlClient('https://example.com/graphql');

$response = $client->query(
	<<<'GRAPHQL'
query route($url: String!) {
  route(url: $url) {
	relative_url
	type
	redirect_code
  }
}
GRAPHQL,
	['url' => '/women/tops-women.html'],
	'route'
);

var_dump($response->getData());
```

## Error handling

The client throws dedicated exceptions:

- `Magento2GraphQLClient\Exception\GraphQlTransportException`
- `Magento2GraphQLClient\Exception\GraphQlResponseException`
- `Magento2GraphQLClient\Exception\GraphQlClientException`

Example:

```php
<?php

use Magento2GraphQLClient\Exception\GraphQlResponseException;
use Magento2GraphQLClient\Exception\GraphQlTransportException;

try {
	$commerce->customerCart();
} catch (GraphQlResponseException $e) {
	var_dump($e->getErrors());
} catch (GraphQlTransportException $e) {
	error_log($e->getMessage());
}
```

## Included operation catalog

Operation strings are centralized in:

- `Magento2GraphQLClient\Operations\AdobeCommerceOperations`

The included set is based on the Adobe Commerce 2.4.8 GraphQL reference and can be extended by adding new constants.

## Compatibility

- Adobe Commerce GraphQL schema reference: 2.4.8
- PHP: 7.4+
- Guzzle: 6.x or 7.x

## Examples

### Product Search Example

Search for products and retrieve store configuration.

Location: `examples/product-search-example.php`

Run it:

```bash
MAGENTO_GRAPHQL_ENDPOINT="https://your-store.example/graphql" \
MAGENTO_GRAPHQL_STORE="default" \
php examples/product-search-example.php "hoodie"
```

Specify the search term as the first argument. If omitted, defaults to `"hoodie"`:

```bash
MAGENTO_GRAPHQL_ENDPOINT="https://your-store.example/graphql" \
php examples/product-search-example.php
```

### Category Listing Example

List all categories in the store with pagination support.

Location: `examples/category-listing-example.php`

Run it:

```bash
MAGENTO_GRAPHQL_ENDPOINT="https://your-store.example/graphql" \
MAGENTO_GRAPHQL_STORE="default" \
MAGENTO_PAGE_SIZE="20" \
MAGENTO_CURRENT_PAGE="1" \
php examples/category-listing-example.php
```

Control pagination with `MAGENTO_PAGE_SIZE` and `MAGENTO_CURRENT_PAGE` environment variables.

### Category Tree Example

Traverse the category hierarchy tree to a specified depth level and display aggregated statistics.

Location: `examples/category-tree-example.php`

Run it:

```bash
MAGENTO_GRAPHQL_ENDPOINT="https://your-store.example/graphql" \
MAGENTO_GRAPHQL_STORE="default" \
php examples/category-tree-example.php 3
```

Specify the maximum level (depth) as the first argument. If omitted, defaults to `2`:

```bash
MAGENTO_GRAPHQL_ENDPOINT="https://your-store.example/graphql" \
php examples/category-tree-example.php
```

Output includes a hierarchical category tree display and aggregated statistics such as total categories by level and children counts.
