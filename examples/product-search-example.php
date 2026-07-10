<?php

declare(strict_types=1);

/**
 * Product Search Example
 *
 * Demonstrates searching for products and retrieving store configuration.
 * Usage:
 *   MAGENTO_GRAPHQL_ENDPOINT="https://your-store.example/graphql" \
 *   MAGENTO_GRAPHQL_STORE="default" \
 *   php examples/product-search-example.php "search-term"
 *
 * Arguments:
 *   search-term: The product search term (defaults to "hoodie" if not provided)
 */

use Magento2GraphQLClient\AdobeCommerceClient;
use Magento2GraphQLClient\GraphQlClient;
use Magento2GraphQLClient\Exception\GraphQlClientException;

require_once __DIR__ . '/../vendor/autoload.php';

$endpoint = getenv('MAGENTO_GRAPHQL_ENDPOINT') ?: '';
if ($endpoint === '') {
    fwrite(STDERR, "Set MAGENTO_GRAPHQL_ENDPOINT, for example: https://your-store.example/graphql\n");
    exit(1);
}

$storeCode = getenv('MAGENTO_GRAPHQL_STORE') ?: null;
$searchTerm = isset($argv[1]) && $argv[1] !== '' ? $argv[1] : 'hoodie';

$baseClient = new GraphQlClient($endpoint);
if (is_string($storeCode) && $storeCode !== '') {
    $baseClient = $baseClient->withStore($storeCode);
}

$client = new AdobeCommerceClient($baseClient);

try {
    echo "=== Store Configuration ===\n";
    $storeConfig = $client->storeConfig();
    echo json_encode($storeConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

    echo "=== Product Search (search=\"" . $searchTerm . "\") ===\n";
    $products = $client->products([
        'search' => $searchTerm,
        'pageSize' => 5,
        'currentPage' => 1,
    ]);

    echo json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (GraphQlClientException $exception) {
    fwrite(STDERR, "GraphQL client error: " . $exception->getMessage() . "\n");
    exit(1);
}
