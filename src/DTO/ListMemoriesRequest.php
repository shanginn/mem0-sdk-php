<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Crell\Serde\Attributes\ClassSettings;
use Crell\Serde\Renaming\Cases;

/**
 * Request DTO for listing memories.
 * Contains parameters for filtering, pagination, and field selection.
 */
#[ClassSettings(renameWith: Cases::snake_case, omitNullFields: true)] // For body serialization
final readonly class ListMemoriesRequest
{
    /**
     * @param Filter|null $filters Filters to apply to the memories.
     *                             Sent in the request body.
     * @param null|array<string> $fields A list of field names to include in the response.
     *                                   If not provided, all fields will be returned. Sent as query parameters.
     * @param string|null $orgId Filter memories by organization ID.
     *                           Overrides client-level default if provided. Sent in the request body.
     * @param string|null $projectId Filter memories by project ID.
     *                               Overrides client-level default if provided. Sent in the request body.
     */
    public function __construct(
        public ?Filter $filters = null,
        public ?array $fields = null,
        public ?string $orgId = null,
        public ?string $projectId = null,
    ) {}
}