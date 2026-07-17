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

### Product Search Example (with custom attributes)

```php
use Magento2GraphQLClient\Types\ProductAttributeFilterInput;
use Magento2GraphQLClient\Types\ProductAttributeSortInput;
use Magento2GraphQLClient\Types\FilterEqualTypeInput;
use Magento2GraphQLClient\Types\ProductInterface;
use Magento2GraphQLClient\Types\Product;
use Magento2GraphQLClient\Types\ComplexTextValue;
use Magento2GraphQLClient\Types\ProductImage;
use Magento2GraphQLClient\Types\ProductCustomAttributes;
use Magento2GraphQLClient\Types\AttributeMetadataError;
use Magento2GraphQLClient\Types\AttributeValueInterface;
use Magento2GraphQLClient\Types\AttributeSelectedOptionInterface;
use Magento2GraphQLClient\Types\AttributeSelectedOptionsInterface;

$searchTerm = 'Hoodie';
$categoryIds = [1,2,3];

$filterCriteria = [
	ProductAttributeFilterInput::CATEGORY_ID => FilterEqualTypeInput::fromValue($category), // in this category
	ProductAttributeFilterInput::SKU => FilterEqualTypeInput::fromValue(['sku1', 'sku2']), // is one of these skus
];
$filter = ProductAttributeFilterInput::create($filterCriteria);

$sort = ProductAttributeSortInput::createWithSort('some_attribute', ProductAttributeSortInput::SORT_DESC);

$r = $client->products(
	Product::withAttributes([
		ProductInterface::TYPE_NAME,
		ProductInterface::NAME,
		ProductInterface::SKU,
		ProductInterface::ID,
		ProductInterface::UID,
		ProductInterface::SHORT_DESCRIPTION => ComplexTextValue::withAttributes([
			ComplexTextValue::HTML,
		]),
		ProductInterface::THUMBNAIL => ProductImage::withAttributes([
			ProductImage::LABEL,
			ProductImage::URL,
		]),
		ProductInterface::RATING_SUMMARY,
		ProductInterface::REVIEW_COUNT,
		ProductInterface::CUSTOM_ATTRIBUTESV2 => ProductCustomAttributes::withAttributes([
			ProductCustomAttributes::ERRORS => AttributeMetadataError::withAttributes([
				AttributeMetadataError::TYPE,
				AttributeMetadataError::MESSAGE,
			]),
			ProductCustomAttributes::ITEMS => AttributeValueInterface::withAttributes([
				AttributeValueInterface::CODE,
			])->on(AttributeValue::TYPE_NAME, AttributeValue::withAttributes([
				AttributeValue::VALUE,
			]))->on(AttributeSelectedOptionsInterface::TYPE_NAME, AttributeSelectedOptionsInterface::withAttributes([
				AttributeSelectedOptionsInterface::SELECTED_OPTIONS => AttributeSelectedOptionInterface::withAttributes([
					AttributeSelectedOptionInterface::LABEL,
					AttributeSelectedOptionInterface::VALUE,
				])
			])),
		])->withFilter(AttributeFilterInput::create([
			AttributeFilterInput::USED_IN_PRODUCT_LISTING => true,
		])),
	]),
	search: $search,
	filter: $filter,
	pageSize: $pageSize,
	currentPage: $currentPage,
	sort: $sort
);
```


### Category Listing Example
Get all categories under a parent ID

```php
use Magento2GraphQLClient\Types\CMSBlock;
use Magento2GraphQLClient\Types\CategoryInterface;
use Magento2GraphQLClient\Types\CategoryFilterInput;
use Magento2GraphQLClient\Types\FilterEqualTypeInput;

$parentCategoryId = 123;

$response = $client->categories(
	[
		CategoryInterface::UID,
		CategoryInterface::ID,
		CategoryInterface::NAME,
		CMSBlock::withAttributes([
			CMSBlock::IDENTIFIER,
			CMSBlock::CONTENT,
		]),
		CategoryInterface::NAME,
		CategoryInterface::INCLUDE_IN_MENU
	],
	[
		'filters' => CategoryFilterInput::forParentId(FilterEqualTypeInput::fromValue($parentCategoryId)),
	]);

foreach(($response['categories']['items'] ?? []) as $category) {
	$data[] = [
		'uid' => $category['uid'],
		'id' => $category['id'],
		'name' => $category['name'],
	];
}
```