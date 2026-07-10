<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Exception;

class GraphQlResponseException extends GraphQlClientException
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private $errors;

    /**
     * @param array<int, array<string, mixed>> $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;

        parent::__construct($this->buildMessage($errors));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<int, array<string, mixed>> $errors
     */
    private function buildMessage(array $errors): string
    {
        if ($errors === []) {
            return 'GraphQL response contains errors';
        }

        $messages = [];

        foreach ($errors as $error) {
            if (isset($error['message']) && is_string($error['message'])) {
                $messages[] = $error['message'];
            }
        }

        if ($messages === []) {
            return 'GraphQL response contains malformed errors';
        }

        return 'GraphQL response contains errors: ' . implode('; ', $messages);
    }
}
