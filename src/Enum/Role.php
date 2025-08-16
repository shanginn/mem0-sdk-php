<?php

declare(strict_types=1);

namespace Mem0\Enum;

use Mem0\_Response\MemoryHistory;

/**
 * Represents the role of a message author in a conversation.
 *
 * @see \Mem0\DTO\Message::$role
 * @see MemoryHistory (input item role)
 */
enum Role: string
{
    case USER      = 'user';
    case ASSISTANT = 'assistant';
}