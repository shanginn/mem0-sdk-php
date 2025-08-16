<?php

declare(strict_types=1);

namespace Mem0\Enum;

/**
 * Specifies the output format for API responses, particularly for memory creation and search.
 *
 * @see \Mem0\_Request\MemoryOptions::$outputFormat
 * @see \Mem0\_Request\SearchOptions::$outputFormat
 */
enum OutputFormat: string
{
    /**
     * Version 1.0 output format. (Note: Python SDK warns this is deprecated).
     * Default for `/v1/memories/search/`.
     */
    case V1_0 = 'v1.0';

    /**
     * Version 1.1 output format. Recommended for more detailed information.
     */
    case V1_1 = 'v1.1';
}