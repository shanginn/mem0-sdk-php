<?php

declare(strict_types=1);

namespace Mem0\Enum;

enum FilterOperator: string
{
    /** Matches if the field's value is one of the provided values. */
    case in = 'in';

    /** Greater than or equal to. For dates or numerical values. */
    case gte = 'gte';

    /** Less than or equal to. For dates or numerical values. */
    case lte = 'lte';

    /** Greater than. For dates or numerical values. */
    case gt = 'gt';

    /** Less than. For dates or numerical values. */
    case lt = 'lt';

    /** Not equal to. */
    case ne = 'ne';

    /** For string fields, matches if the field contains the given substring (case-sensitive). */
    case contains = 'contains';

    /** For string fields, matches if the field contains the given substring (case-insensitive). */
    case icontains = 'icontains';

    public static function gt($value): array
    {
        return [self::gt->value => $value];
    }
}