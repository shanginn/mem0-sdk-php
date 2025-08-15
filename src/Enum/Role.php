<?php

declare(strict_types=1);

namespace Mem0\Enum;

/**
 * Represents the role of a message author in a conversation.
 * @see \Mem0\DTO\Message::$role
 * @see \Mem0\_Response\MemoryHistory (input item role)
 */
enum Role: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
}