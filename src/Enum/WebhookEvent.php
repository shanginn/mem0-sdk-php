<?php

declare(strict_types=1);

namespace Mem0\Enum;

/**
 * Represents the types of events that can trigger a webhook.
 * @see \Mem0\_Request\CreateWebhookPayload::$eventTypes
 * @see \Mem0\_Request\UpdateWebhookPayload::$eventTypes
 * @see \Mem0\_Response\Webhook::$eventTypes
 */
enum WebhookEvent: string
{
    /**
     * Triggered when a memory is added.
     */
    case MEMORY_ADDED = 'memory_add'; // OpenAPI uses "memory:add", "memory:update", "memory:delete"

    /**
     * Triggered when a memory is updated.
     */
    case MEMORY_UPDATED = 'memory_update';

    /**
     * Triggered when a memory is deleted.
     */
    case MEMORY_DELETED = 'memory_delete';
}