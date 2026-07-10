<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\AbstractFragment;

class Product extends AbstractFragment
{
    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [
        ProductInterface::TYPE_NAME => self::SIMPLE_TYPE,
        ProductInterface::ATTRIBUTE_SET_ID => self::SIMPLE_TYPE,
        ProductInterface::CANONICAL_URL => self::SIMPLE_TYPE,
        ProductInterface::COUNTRY_OF_MANUFACTURE => self::SIMPLE_TYPE,
        ProductInterface::CREATED_AT => self::SIMPLE_TYPE,
        ProductInterface::GIFT_MESSAGE_AVAILABLE => self::SIMPLE_TYPE,
        ProductInterface::GIFT_WRAPPING_AVAILABLE => self::SIMPLE_TYPE,
        ProductInterface::GIFT_WRAPPING_PRICE => self::SIMPLE_TYPE,
        ProductInterface::ID => self::SIMPLE_TYPE,
        ProductInterface::IMAGE => self::SIMPLE_TYPE,
        ProductInterface::IS_RETURNABLE => self::SIMPLE_TYPE,
        ProductInterface::MANUFACTURER => self::SIMPLE_TYPE,
        ProductInterface::MAX_SALE_QTY => self::SIMPLE_TYPE,
        ProductInterface::META_DESCRIPTION => self::SIMPLE_TYPE,
        ProductInterface::META_KEYWORD => self::SIMPLE_TYPE,
        ProductInterface::META_TITLE => self::SIMPLE_TYPE,
        ProductInterface::MIN_SALE_QTY => self::SIMPLE_TYPE,
        ProductInterface::NAME => self::SIMPLE_TYPE,
        ProductInterface::NEW_FROM_DATE => self::SIMPLE_TYPE,
        ProductInterface::NEW_TO_DATE => self::SIMPLE_TYPE,
        ProductInterface::ONLY_X_LEFT_IN_STOCK => self::SIMPLE_TYPE,
        ProductInterface::OPTIONS_CONTAINER => self::SIMPLE_TYPE,
        ProductInterface::QUANTITY => self::SIMPLE_TYPE,
        ProductInterface::RATING_SUMMARY => self::SIMPLE_TYPE,
        ProductInterface::REVIEW_COUNT => self::SIMPLE_TYPE,
        ProductInterface::SALE => self::SIMPLE_TYPE,
        ProductInterface::SKU => self::SIMPLE_TYPE,
        ProductInterface::SPECIAL_FROM_DATE => self::SIMPLE_TYPE,
        ProductInterface::SPECIAL_PRICE => self::SIMPLE_TYPE,
        ProductInterface::SPECIAL_TO_DATE => self::SIMPLE_TYPE,
        ProductInterface::STAGED => self::SIMPLE_TYPE,
        ProductInterface::SWATCH_IMAGE => self::SIMPLE_TYPE,
        ProductInterface::TIER_PRICE => self::SIMPLE_TYPE,
        ProductInterface::TIER_PRICES => self::SIMPLE_TYPE,
        ProductInterface::TYPE_ID => self::SIMPLE_TYPE,
        ProductInterface::UID => self::SIMPLE_TYPE,
        ProductInterface::UPDATED_AT => self::SIMPLE_TYPE,
        ProductInterface::URL_KEY => self::SIMPLE_TYPE,
        ProductInterface::URL_PATH => self::SIMPLE_TYPE,
        ProductInterface::URL_SUFFIX => self::SIMPLE_TYPE,

        ProductInterface::DESCRIPTION => ComplexTextValue::class,
        ProductInterface::SHORT_DESCRIPTION => ComplexTextValue::class,
        ProductInterface::THUMBNAIL => ProductImage::class,
        ProductInterface::PRICE_TIERS => TierPrice::class,
        ProductInterface::CUSTOM_ATTRIBUTESV2 => ProductCustomAttributes::class,
        ProductInterface::MEDIA_GALLERY => MediaGalleryInterface::class,
    ];

    /** 
     * @var array<class-string<AbstractFragment>, string> $subFragments
     */
    protected array $subFragments = [
        // ProductInterface::CATEGORIES,
        // ProductInterface::CROSSSELL_PRODUCTS,
        // ProductInterface::MEDIA_GALLERY,
        // ProductInterface::PRICE,
        // ProductInterface::PRICE_RANGE,
        // ProductInterface::PRODUCT_LINKS,
        // ProductInterface::RELATED_PRODUCTS,
        // ProductInterface::REVIEWS,
        // ProductInterface::RULES,
        // ProductInterface::SMALL_IMAGE,
        // ProductInterface::STOCK_STATUS,
        // ProductInterface::UPSELL_PRODUCTS,
        // ProductInterface::URL_REWRITES,
        // ProductInterface::WEBSITES,
    ];
}