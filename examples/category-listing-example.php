<?php

declare(strict_types=1);

/**
 * Category Listing Example
 *
 * Demonstrates retrieving and listing product categories.
 * Usage:
 *   MAGENTO_GRAPHQL_ENDPOINT="https://your-store.example/graphql" \
 *   MAGENTO_GRAPHQL_STORE="default" \
 *   php examples/category-listing-example.php
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
$pageSize = (int) (getenv('MAGENTO_PAGE_SIZE') ?: '20');
$currentPage = (int) (getenv('MAGENTO_CURRENT_PAGE') ?: '1');

$baseClient = new GraphQlClient($endpoint);
if (is_string($storeCode) && $storeCode !== '') {
    $baseClient = $baseClient->withStore($storeCode);
}

$client = new AdobeCommerceClient($baseClient);

try {
    echo "=== Listing Categories ===\n";
    $categories = $client->categories([
        'pageSize' => $pageSize,
        'currentPage' => $currentPage,
    ]);

    echo json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

    if (isset($categories['categories']['items']) && is_array($categories['categories']['items'])) {
        echo "\n=== Category Summary ===\n";
        foreach ($categories['categories']['items'] as $category) {
            $id = $category['id'] ?? $category['uid'] ?? 'N/A';
            $name = $category['name'] ?? 'Unnamed';
            $level = $category['level'] ?? 'N/A';
            printf("[%s] Level %s: %s\n", $id, $level, $name);
        }
    }
} catch (GraphQlClientException $exception) {
    fwrite(STDERR, "GraphQL client error: " . $exception->getMessage() . "\n");
    exit(1);
}
