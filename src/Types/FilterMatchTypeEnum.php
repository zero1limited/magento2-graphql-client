<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

enum FilterMatchTypeEnum: string
{
    case Exact = 'EXACT';
    case Partial = 'PARTIAL';
}