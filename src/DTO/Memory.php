<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Crell\Serde\Attributes\ClassSettings;
use Crell\Serde\Attributes\SequenceField;
use Crell\Serde\Renaming\Cases;
use Mem0\Enum\EventType;

/**
 * Represents a memory object.
 * Returned by various endpoints like get, getAll, search, add.
 * Structure can vary slightly based on the endpoint and `output_format`.
 */
#[ClassSettings(renameWith: Cases::snake_case, omitNullFields: true)]
final readonly class Memory
{
    public function __construct(
        public string $id,
        #[SequenceField(arrayType: Message::class)]
        public ?array $messages = null,
        public ?EventType $event = null,
        public ?MemoryData $data = null,
        public ?string $memory = null,
        public ?string $userId = null,
        public ?string $hash = null,
        /** @var string[]|null */
        #[SequenceField(arrayType: 'string')]
        public ?array $categories = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public ?string $memoryType = null,
        public ?float $score = null,
        public mixed $metadata = null,
        public ?string $owner = null,
        public ?string $agentId = null,
        public ?string $appId = null,
        public ?string $runId = null,
    )
    {
    }
}