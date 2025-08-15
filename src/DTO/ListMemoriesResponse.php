<?php

declare(strict_types=1);

namespace Mem0\DTO;

use Crell\Serde\Attributes\SequenceField;

use Crell\Serde\Attributes as Serde;

class ListMemoriesResponse
{
    /**
     * @param array<Memory> $memories
     */
    public function __construct(
        #[SequenceField(arrayType: Memory::class)]
        public array $memories = [],
    )
    {
    }
}