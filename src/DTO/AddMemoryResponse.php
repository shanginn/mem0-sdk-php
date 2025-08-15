<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Crell\Serde\Attributes\SequenceField;

class AddMemoryResponse
{
    public function __construct(
        #[SequenceField(arrayType: AddMemoryResponseItem::class)]
        public array $results
    ) {
    }
}