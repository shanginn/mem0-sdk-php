<?php

declare(strict_types=1);

namespace Mem0\Enum;

/**
 * Represents the type of feedback that can be submitted for a memory.
 *
 * @see \Mem0\_Request\FeedbackPayload::$feedback
 */
enum FeedbackOption: string
{
    case POSITIVE      = 'POSITIVE';
    case NEGATIVE      = 'NEGATIVE';
    case VERY_NEGATIVE = 'VERY_NEGATIVE';
}