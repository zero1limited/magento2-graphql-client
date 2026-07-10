<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\AdobeCommerceClient;

use Magento2GraphQLClient\Operations\AdobeCommerceOperations;
use Magento2GraphQLClient\GraphQlClient;
use Magento2GraphQLClient\Types\CategoryInterface;
use Magento2GraphQLClient\Types\CMSBlock;
use Magento2GraphQLClient\Types\Product;
use Magento2GraphQLClient\Types\ProductAttributeFilterInput;
use Magento2GraphQLClient\Types\ProductAttributeSortInput;

class Products
{
    public const OPERATION_NAME = 'products';
    public const QUERYz = <<<'GRAPHQL'
query products(
  $search: String,
  $filter: ProductAttributeFilterInput,
  $pageSize: Int,
  $currentPage: Int,
  $sort: ProductAttributeSortInput
) {
  products(
    search: $search,
    filter: $filter,
    pageSize: $pageSize,
    currentPage: $currentPage,
    sort: $sort
  ) {
    total_count
    items {
      uid
      sku
      name
      url_key
      stock_status
      price_range {
        minimum_price {
          regular_price {
            value
            currency
          }
        }
      }
      price_tiers {
        quantity
        final_price {
          value
          currency
        }
      }
      custom_attributesV2(filters: {used_in_product_listing: true}) {
        items {
            code
            ... on AttributeValue {
                value
            }
            ... on AttributeSelectedOptions {
                selected_options {
                    label
                    value
                }
            }
        },
        errors {
            type
            message
        }
      }
    }
    page_info {
      current_page
      page_size
      total_pages
    }
  }
}
GRAPHQL;
public const QUERY = <<<'GRAPHQL'
query products(
  $search: String,
  $filter: ProductAttributeFilterInput,
  $pageSize: Int,
  $currentPage: Int,
  $sort: ProductAttributeSortInput
) {
  products(
    search: $search,
    filter: $filter,
    pageSize: $pageSize,
    currentPage: $currentPage,
    sort: $sort
  ) {
    total_count
    items { %s }
    page_info {
      current_page
      page_size
      total_pages
    }
  }
}
GRAPHQL;

    public function __construct(
        protected GraphQlClient $client
    ) {
    }

    public function __invoke(Product $product, ?string $search = null, ?ProductAttributeFilterInput $filter = null, ?int $pageSize = null, ?int $currentPage = null, ?ProductAttributeSortInput $sort = null): array
    {
      $variables = [];
      if($search !== null && $search !== '') {
        $variables['search'] = $search;
      }
      if($filter !== null) {
        $variables['filter'] = $filter;
      }
      if($pageSize !== null) {
        $variables['pageSize'] = $pageSize;
      }
      if($currentPage !== null) {
        $variables['currentPage'] = $currentPage;
      }
      if($sort !== null) {
        $variables['sort'] = $sort;
      }
      return $this->client
          ->query(
              sprintf(self::QUERY, $product->__toString()),
              $variables,
              self::OPERATION_NAME
          )
          ->getData();
    }
}