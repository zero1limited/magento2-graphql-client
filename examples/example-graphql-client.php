<?php

declare(strict_types=1);

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
$email = getenv('MAGENTO_CUSTOMER_EMAIL') ?: '';
$password = getenv('MAGENTO_CUSTOMER_PASSWORD') ?: '';

$baseClient = new GraphQlClient($endpoint);
if (is_string($storeCode) && $storeCode !== '') {
    $baseClient = $baseClient->withStore($storeCode);
}

$client = new AdobeCommerceClient($baseClient);

try {
    $storeConfig = $client->storeConfig();
    echo "Store config:\n";
    echo json_encode($storeConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

    $products = $client->products([
        'search' => 'buy',
        'pageSize' => 5,
        'currentPage' => 1,
    ]);

    echo "Products (search=hoodie):\n";
    echo json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

    if ($email !== '' && $password !== '') {
        $token = $client->generateCustomerToken($email, $password);
        echo "Generated customer token:\n";
        echo $token . "\n";
    } else {
        echo "Skipping token generation. Set MAGENTO_CUSTOMER_EMAIL and MAGENTO_CUSTOMER_PASSWORD to enable it.\n";
    }
} catch (GraphQlClientException $exception) {
    fwrite(STDERR, "GraphQL client error: " . $exception->getMessage() . "\n");
    exit(1);
}
