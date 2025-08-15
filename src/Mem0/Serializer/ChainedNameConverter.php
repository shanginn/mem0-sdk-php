<?php

declare(strict_types=1);

namespace Mem0\Mem0\Serializer;


use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class ChainedNameConverter implements NameConverterInterface
{
    /**
     * @param array<NameConverterInterface> $nameConverters
     */
    public function __construct(private array $nameConverters = [])
    {
    }

    public function normalize(string $propertyName, string $class = null, string $format = null, array $context = []): string
    {
        foreach ($this->nameConverters as $nameConverter) {
            $propertyName = $nameConverter->normalize($propertyName, $class, $format, $context);
        }

        return $propertyName;
    }

    public function denormalize(string $propertyName, string $class = null, string $format = null, array $context = []): string
    {
        foreach ($this->nameConverters as $nameConverter) {
            $propertyName = $nameConverter->denormalize($propertyName, $class, $format, $context);
        }

        return $propertyName;
    }
}