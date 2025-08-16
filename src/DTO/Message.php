<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Mem0\Enum\Role;

final readonly class Message
{
    /**
     * @param Role   $role    The role of the message author (e.g., 'user', 'assistant').
     * @param string $content the content of the message
     */
    public function __construct(
        public Role $role,
        public string $content,
    ) {}
}