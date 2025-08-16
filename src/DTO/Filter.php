<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * Defines filters to apply when listing memories.
 * Supports direct field matching, comparison operators using FilterOperator,
 * and logical AND/OR combinations of other Filter objects.
 */
final readonly class Filter
{
    /**
     * @param array<FilterOperator|array<mixed>>|null $and logical AND: an array of Filter objects that must all be true
     * @param array<FilterOperator|array<mixed>>|null $or  logical OR: an array of Filter objects, one of which must be true
     */
    public function __construct(
        #[SerializedName('AND')]
        public ?array $and = null,
        #[SerializedName('OR')]
        public ?array $or = null,
    ) {}
}