<?php

declare(strict_types=1);

namespace Mem0\Contract;

interface ClientInterface
{
    public function sendRequest(
        string $method,
        string $endpoint,
        string $body = '',
        array $queryParams = []
    ): string;
}