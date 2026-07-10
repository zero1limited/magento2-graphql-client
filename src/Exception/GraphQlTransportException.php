<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Exception;

use Throwable;

class GraphQlTransportException extends GraphQlClientException
{
    public static function fromThrowable(Throwable $throwable): self
    {
        return new self(
            sprintf('GraphQL transport error: %s', $throwable->getMessage()),
            (int) $throwable->getCode(),
            $throwable
        );
    }
}
