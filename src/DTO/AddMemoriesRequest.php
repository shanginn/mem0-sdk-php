<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Crell\Serde\Attributes as Serde;
use Crell\Serde\Attributes\DateField;
use Crell\Serde\Renaming\Cases;
use DateTimeInterface;
use Mem0\Enum\ApiVersion;
use Mem0\Enum\OutputFormat;

/**
 * Payload for creating memories. Maps to the 'MemoryInput' schema.
 */
#[Serde\ClassSettings(renameWith: Cases::snake_case, omitNullFields: true)]
final readonly class AddMemoriesRequest
{
    /**
     * @param array<Message>            $messages           Array of message objects.
     *                                                      Using `array<string,string|null>` allows for more flexible message structures if needed.
     *                                                      Using `MessageInput` enforces `role` and `content`.
     * @param array<string, mixed>|null $metadata           free-form metadata object
     * @param array<string, mixed>|null $customCategories   free-form custom categories object
     * @param DateTimeInterface|null    $expirationDate     Expiration date (YYYY-MM-DD).
     *                                                      If \DateTimeInterface, it should be formatted to 'Y-m-d' before serialization.
     * @param int|null                  $timestamp          unix timestamp
     * @param ?string                   $agentId
     * @param ?string                   $userId
     * @param ?string                   $appId
     * @param ?string                   $runId
     * @param ?string                   $includes
     * @param ?string                   $excludes
     * @param bool                      $infer
     * @param ?OutputFormat             $outputFormat
     * @param ?string                   $customInstructions
     * @param bool                      $immutable
     * @param ?string                   $orgId
     * @param ?string                   $projectId
     * @param ?ApiVersion               $version
     */
    public function __construct(
        public array $messages,
        public ?string $agentId = null,
        public ?string $userId = null,
        public ?string $appId = null,
        public ?string $runId = null,
        public ?array $metadata = null,
        public ?string $includes = null,
        public ?string $excludes = null,
        public bool $infer = true,
        public ?OutputFormat $outputFormat = OutputFormat::V1_1,
        public ?array $customCategories = null,
        public ?string $customInstructions = null,
        public bool $immutable = false,
        public ?int $timestamp = null,
        #[DateField(format: 'Y-m-d')]
        public ?DateTimeInterface $expirationDate = null,
        public ?string $orgId = null,
        public ?string $projectId = null,
        public ?ApiVersion $version = ApiVersion::V2
    ) {}
}