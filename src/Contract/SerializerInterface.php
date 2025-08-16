<?php

declare(strict_types=1);

namespace Mem0\Contract;

use Mem0\Exception\DeserializationException;

interface SerializerInterface
{
    public function serialize(mixed $data): string;

    /**
     * @template T
     *
     * @param string          $serialized
     * @param class-string<T> $to
     * @param bool            $isArray
     *
     * @throws DeserializationException
     *
     * @return ($isArray is true ? array<T> : T)
     */
    public function deserialize(string $serialized, string $to, bool $isArray = false): mixed;
}