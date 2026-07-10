<?php

declare(strict_types=1);

/**
 * Category Tree Traversal Example
 *
 * Demonstrates recursively traversing the category tree structure to a specified depth.
 * Fetches parent categories, then recursively queries for children using parent_id filter.
 *
 * Usage:
 *   MAGENTO_GRAPHQL_ENDPOINT="https://your-store.example/graphql" \
 *   MAGENTO_GRAPHQL_STORE="default" \
 *   php examples/category-tree-example.php 3
 *
 * Arguments:
 *   maxDepth: Maximum depth to traverse (defaults to 2 if not provided)
 */

use Magento2GraphQLClient\GraphQlClient;
use Magento2GraphQLClient\Exception\GraphQlClientException;

require_once __DIR__ . '/../vendor/autoload.php';

$endpoint = getenv('MAGENTO_GRAPHQL_ENDPOINT') ?: '';
if ($endpoint === '') {
    fwrite(STDERR, "Set MAGENTO_GRAPHQL_ENDPOINT, for example: https://your-store.example/graphql\n");
    exit(1);
}

$storeCode = getenv('MAGENTO_GRAPHQL_STORE') ?: null;
$maxDepth = isset($argv[1]) && is_numeric($argv[1]) ? (int)$argv[1] : 2;

$baseClient = new GraphQlClient($endpoint);
if (is_string($storeCode) && $storeCode !== '') {
    $baseClient = $baseClient->withStore($storeCode);
}

// Statistics aggregation
$stats = [
    'totalCategories' => 0,
    'depthStats' => [],
];

// Category node with depth tracking
class CategoryNode {
    public $id;
    public $name;
    public $childrenCount;
    public $depth;
    public $children = [];

    public function __construct($data, $depth) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? 'Unnamed';
        $this->childrenCount = $data['children_count'] ?? 0;
        $this->depth = $depth;
    }
}

/**
 * Recursively fetch categories by parent_id up to maxDepth
 */
function fetchCategoryChildren($client, $parentId, $currentDepth, $maxDepth, &$stats) {
    if ($currentDepth > $maxDepth) {
        return [];
    }

    try {
        $filter = [
            'parent_id' => ['eq' => (string)$parentId],
        ];

        $response = $client->query(
            <<<'GRAPHQL'
query categoriesFilter($filters: CategoryFilterInput, $pageSize: Int) {
  categories(filters: $filters, pageSize: $pageSize) {
    items {
      id
      name
      children_count
    }
  }
}
GRAPHQL,
            [
                'filters' => $filter,
                'pageSize' => 100,
            ]
        );

        $categories = $response->path('data.categories.items') ?: [];
        $nodes = [];

        foreach ($categories as $catData) {
            $node = new CategoryNode($catData, $currentDepth);
            $stats['totalCategories']++;

            // Track depth statistics
            if (!isset($stats['depthStats'][$currentDepth])) {
                $stats['depthStats'][$currentDepth] = 0;
            }
            $stats['depthStats'][$currentDepth]++;

            // Recursively fetch children if not at max depth and category has children
            if ($currentDepth < $maxDepth && $node->childrenCount > 0) {
                $node->children = fetchCategoryChildren($client, $node->id, $currentDepth + 1, $maxDepth, $stats);
            }

            $nodes[] = $node;
        }

        return $nodes;
    } catch (Exception $e) {
        fwrite(STDERR, "Error fetching categories at depth $currentDepth: " . $e->getMessage() . "\n");
        return [];
    }
}

/**
 * Print category tree with proper indentation
 */
function printCategoryTree($nodes, $depth = 1) {
    foreach ($nodes as $index => $node) {
        $isLast = ($index === count($nodes) - 1);

        // Print tree branch
        $indent = str_repeat('  ', max(0, $depth - 1));
        $branch = $isLast ? '└─ ' : '├─ ';

        echo $indent . $branch . $node->name . ' (ID: ' . $node->id . ')';

        if ($node->childrenCount > 0) {
            echo ' [' . $node->childrenCount . ' children]';
        }
        echo "\n";

        // Recursively print children
        if (!empty($node->children)) {
            printCategoryTree($node->children, $depth + 1);
        }
    }
}

try {
    echo "=== Category Tree (Max Depth: $maxDepth) ===\n\n";

    // Fetch root categories (no parent filter - these are top-level categories)
    $response = $baseClient->query(
        <<<'GRAPHQL'
query {
  categories(pageSize: 100) {
    items {
      id
      name
      children_count
    }
  }
}
GRAPHQL
    );

    $rootCategories = $response->path('data.categories.items') ?: [];

    if (empty($rootCategories)) {
        echo "No categories found in the store.\n";
        exit(0);
    }

    // Build tree recursively
    $categoryTree = [];
    foreach ($rootCategories as $catData) {
        $node = new CategoryNode($catData, 1);
        $stats['totalCategories']++;

        if (!isset($stats['depthStats'][1])) {
            $stats['depthStats'][1] = 0;
        }
        $stats['depthStats'][1]++;

        // Fetch children if maxDepth > 1 and category has children
        if ($maxDepth > 1 && $node->childrenCount > 0) {
            $node->children = fetchCategoryChildren($baseClient, $node->id, 2, $maxDepth, $stats);
        }

        $categoryTree[] = $node;
    }

    echo "Category Hierarchy:\n";
    echo "───────────────────\n";
    printCategoryTree($categoryTree);

    // Aggregated statistics
    echo "\n=== Aggregated Statistics ===\n";
    echo "Total Categories Fetched: " . $stats['totalCategories'] . "\n";
    echo "\nBreakdown by Depth:\n";

    ksort($stats['depthStats']);
    foreach ($stats['depthStats'] as $depth => $count) {
        echo "  Depth $depth: $count categories\n";
    }

} catch (GraphQlClientException $exception) {
    fwrite(STDERR, "GraphQL client error: " . $exception->getMessage() . "\n");
    exit(1);
}

