<?php

declare(strict_types=1);

namespace Mem0\Contract;

use Amp\Http\Client\Request;
use Amp\Http\Client\Response;

interface ClientInterface
{
    public function sendRequest(
        string $method,
        string $endpoint,
        string $body = '',
        array $queryParams = []
    ): string;
}