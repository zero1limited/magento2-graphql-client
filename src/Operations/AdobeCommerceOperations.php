<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Operations;

final class AdobeCommerceOperations
{
    public const AVAILABLE_STORES = <<<'GRAPHQL'
query availableStores($useCurrentGroup: Boolean) {
  availableStores(useCurrentGroup: $useCurrentGroup) {
    id
    code
    store_code
    store_name
    website_id
    website_code
    website_name
    locale
    base_currency_code
    default_display_currency_code
  }
}
GRAPHQL;

    public const STORE_CONFIG = <<<'GRAPHQL'
query storeConfig {
  storeConfig {
    store_code
    store_name
    locale
    base_url
    secure_base_url
    root_category_id
    default_display_currency_code
  }
}
GRAPHQL;

    public const PRODUCTS = <<<'GRAPHQL'
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

    public const CATEGORIES = <<<'GRAPHQL'
query categories(
  $filters: CategoryFilterInput,
  $pageSize: Int,
  $currentPage: Int
) {
  categories(filters: $filters, pageSize: $pageSize, currentPage: $currentPage) {
    total_count
    items {
      uid
      id
      name
      url_path
      url_key
      level
      children_count
    }
    page_info {
      current_page
      page_size
      total_pages
    }
  }
}
GRAPHQL;

    public const CMS_PAGE = <<<'GRAPHQL'
query cmsPage($id: Int, $identifier: String) {
  cmsPage(id: $id, identifier: $identifier) {
    identifier
    title
    content_heading
    content
    meta_title
    meta_description
    url_key
  }
}
GRAPHQL;

    public const CMS_BLOCKS = <<<'GRAPHQL'
query cmsBlocks($identifiers: [String]) {
  cmsBlocks(identifiers: $identifiers) {
    items {
      identifier
      title
      content
    }
  }
}
GRAPHQL;

    public const CART = <<<'GRAPHQL'
query cart($cart_id: String!) {
  cart(cart_id: $cart_id) {
    id
    email
    is_virtual
    total_quantity
    itemsV2 {
      total_count
      items {
        uid
        quantity
        product {
          uid
          sku
          name
        }
      }
    }
    prices {
      grand_total {
        value
        currency
      }
      subtotal_excluding_tax {
        value
        currency
      }
    }
  }
}
GRAPHQL;

    public const CUSTOMER_CART = <<<'GRAPHQL'
query customerCart {
  customerCart {
    id
    email
    total_quantity
    itemsV2 {
      total_count
      items {
        uid
        quantity
        product {
          uid
          sku
          name
        }
      }
    }
  }
}
GRAPHQL;

    public const CUSTOMER = <<<'GRAPHQL'
query customer {
  customer {
    id
    firstname
    lastname
    email
    is_subscribed
    group_id
  }
}
GRAPHQL;

    public const ROUTE = <<<'GRAPHQL'
query route($url: String!) {
  route(url: $url) {
    redirect_code
    relative_url
    type
  }
}
GRAPHQL;

    public const GENERATE_CUSTOMER_TOKEN = <<<'GRAPHQL'
mutation generateCustomerToken($email: String!, $password: String!) {
  generateCustomerToken(email: $email, password: $password) {
    token
  }
}
GRAPHQL;

    public const CREATE_EMPTY_CART = <<<'GRAPHQL'
mutation createEmptyCart {
  createEmptyCart
}
GRAPHQL;

    public const CREATE_CUSTOMER_CART = <<<'GRAPHQL'
mutation createCustomerCart {
  createCustomerCart
}
GRAPHQL;
}
