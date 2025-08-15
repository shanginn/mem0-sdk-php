<?php

declare(strict_types=1);

namespace Mem0\DTO;

/**
 * Represents the core data of a memory, typically the textual content.
 * Used within the Memory response object when memories are created with `output_format=v1.1` for the `add` endpoint.
 * @see \Mem0\DTO\Memory::$data (when `output_format` is `v1.1` on add)
 */
final readonly class MemoryData
{
    /**
     * @param string $memory The textual content of the memory.
     */
    public function __construct(
        public string $memory
    ) {}
}