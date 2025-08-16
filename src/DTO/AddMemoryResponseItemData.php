<?php

declare(strict_types=1);

namespace Mem0\DTO;

class AddMemoryResponseItemData
{
    /**
     * @param string $memory the actual memory content string
     */
    public function __construct(
        public string $memory
    ) {}
}