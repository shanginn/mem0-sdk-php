<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Crell\Serde\Attributes\ClassSettings;
use Crell\Serde\Renaming\Cases;
use DateTimeInterface;

/**
 * Represents comparison operators for filtering.
 * Used as a value for a field in the Filter DTO when complex conditions are needed.
 * For example: "created_at": {"gte": "2024-07-01", "lte": "2024-07-31"}.
 */
#[ClassSettings(renameWith: Cases::snake_case, omitNullFields: true)]
final readonly class FilterOperator
{
    /**
     * @param array<string|int|float>|null  $in        matches if the field's value is one of the provided values
     * @param string|DateTimeInterface|null $gte       Greater than or equal to. For dates or numerical values.
     * @param string|DateTimeInterface|null $lte       Less than or equal to. For dates or numerical values.
     * @param string|DateTimeInterface|null $gt        Greater than. For dates or numerical values.
     * @param string|DateTimeInterface|null $lt        Less than. For dates or numerical values.
     * @param string|int|float|bool|null    $ne        not equal to
     * @param string|null                   $contains  for string fields, matches if the field contains the given substring (case-sensitive)
     * @param string|null                   $icontains for string fields, matches if the field contains the given substring (case-insensitive)
     */
    public function __construct(
        public ?array $in = null,
        public null|DateTimeInterface|string $gte = null,
        public null|DateTimeInterface|string $lte = null,
        public null|DateTimeInterface|string $gt = null,
        public null|DateTimeInterface|string $lt = null,
        public null|bool|float|int|string $ne = null,
        public ?string $contains = null,
        public ?string $icontains = null,
    ) {}
}