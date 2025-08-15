<?php

declare(strict_types=1);

namespace Mem0\Enum;

enum MemoryEvent: string
{
    case ADD = 'ADD';
    case UPDATE = 'UPDATE';
    case DELETE = 'DELETE';
}