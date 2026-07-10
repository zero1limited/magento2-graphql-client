<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\AdobeCommerceClient;

use Magento2GraphQLClient\Operations\AdobeCommerceOperations;
use Magento2GraphQLClient\GraphQlClient;
use Magento2GraphQLClient\Types\CategoryInterface;
use Magento2GraphQLClient\Types\CMSBlock;

class Categories
{
    public const OPERATION_NAME = 'categories';
    public const QUERY = <<<'GRAPHQL'
query categories(
  $filters: CategoryFilterInput,
  $pageSize: Int,
  $currentPage: Int
) {
  categories(filters: $filters, pageSize: $pageSize, currentPage: $currentPage) {
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

    public function __invoke(array $attributes = [
      CategoryInterface::UID,
      CategoryInterface::ID,
      CategoryInterface::NAME,
    ], array $variables = []): array
    {
      $attr = [];
      foreach($attributes as $attribute) {
        if(is_string($attribute)) {
          $attr[] = $attribute;
        } elseif(is_object($attribute)) {
          if($attribute instanceof CMSBlock){
            $attr[] = sprintf('%s {%s}', CategoryInterface::CMS_BLOCK, $attribute);
          }else{
            throw new \InvalidArgumentException('Invalid attribute class: '.get_class($attribute));
          }
        }else{
          throw new \InvalidArgumentException('Invalid attribute type: '.gettype($attribute));
        }
      }
      return $this->client
          ->query(
              sprintf(self::QUERY, implode(' ', $attr)),
              $variables,
              self::OPERATION_NAME
          )
          ->getData();
    }
}