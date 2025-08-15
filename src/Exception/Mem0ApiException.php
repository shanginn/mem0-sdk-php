<?php

declare(strict_types=1);

namespace Mem0\Exception;

/**
 * Represents an error returned by the Mem0 API.
 */
class Mem0ApiException extends Mem0Exception
{
    /**
     * @param string $message The primary error message from the API.
     * @param int $statusCode The HTTP status code, if available (0 if not).
     * @param array<string, mixed>|null $errorDetails The full decoded error response from the API, if available.
     * @param string|null $responseBody The raw response body, for debugging.
     */
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
//        public readonly ?array $errorDetails = null,
        public readonly ?string $responseBody = null,
    ) {
        parent::__construct($message, $statusCode);
    }
}