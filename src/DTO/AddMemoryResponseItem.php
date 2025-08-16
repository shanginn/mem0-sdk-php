<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Mem0\Enum\MemoryEvent;

class AddMemoryResponseItem
{
    /**
     * @param string      $id     ID of the created/affected memory
     * @param string      $memory contains the memory string
     * @param MemoryEvent $event  the type of event (ADD, UPDATE, DELETE)
     */
    public function __construct(
        public string $id,
        public string $memory,
        public MemoryEvent $event
    ) {}
}