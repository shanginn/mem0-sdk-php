<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Crell\Serde\Attributes\ClassSettings;
use Crell\Serde\Attributes\SequenceField;
use Crell\Serde\Renaming\Cases;

/**
 * Request structure for searching memories using the v2 search API.
 * Supports advanced filtering with logical operators (AND, OR, NOT) and comparison operators.
 */
#[ClassSettings(renameWith: Cases::snake_case, omitNullFields: true)]
final readonly class SearchMemoriesRequest
{
    /**
     * @param string $query The query to search for in the memory.
     * @param array|Filter|null $filters A dictionary of filters to apply to the search. Supports logical operators (AND, OR) and comparison operators.
     * @param int|null $topK The number of top results to return. Default: 10.
     * @param array<string>|null $fields A list of field names to include in the response. If not provided, all fields will be returned.
     * @param bool|null $rerank Whether to rerank the memories. Default: false.
     * @param bool|null $keywordSearch Whether to search for memories based on keywords. Default: false.
     * @param bool|null $filterMemories Whether to filter the memories. Default: false.
     * @param float|null $threshold The minimum similarity threshold for returned results. Default: 0.3.
     * @param string|null $orgId The unique identifier of the organization associated with the memory.
     * @param string|null $projectId The unique identifier of the project associated with the memory.
     */
    public function __construct(
        public string $query,
        public array|Filter|null $filters = null,
        public ?int $topK = null,
        #[SequenceField(arrayType: 'string')]
        public ?array $fields = null,
        public ?bool $rerank = null,
        public ?bool $keywordSearch = null,
        public ?bool $filterMemories = null,
        public ?float $threshold = null,
        public ?string $orgId = null,
        public ?string $projectId = null,
    ) {}
}