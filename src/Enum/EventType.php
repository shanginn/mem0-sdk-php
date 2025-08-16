<?php

declare(strict_types=1);

namespace Mem0\Enum;

/**
 * Represents the type of event that occurred for a memory.
 */
enum EventType: string
{
    /**
     * Indicates that a memory was added.
     *
     * @see \Mem0\_Response\MemoryHistory::$event
     * @see \Mem0\DTO\Memory::$event (from `/v1/memories/ post` response)
     */
    case ADD = 'ADD';

    /**
     * Indicates that a memory was updated.
     *
     * @see \Mem0\_Response\MemoryHistory::$event
     * @see \Mem0\DTO\Memory::$event (from `/v1/memories/ post` response)
     */
    case UPDATE = 'UPDATE';

    /**
     * Indicates that a memory was deleted.
     *
     * @see \Mem0\_Response\MemoryHistory::$event
     * @see \Mem0\DTO\Memory::$event (from `/v1/memories/ post` response)
     */
    case DELETE = 'DELETE';

    /**
     * Indicates that no operation was performed on the memory.
     * (As seen in TS client, might not be in OpenAPI directly for event field).
     */
    case NOOP = 'NOOP';
}