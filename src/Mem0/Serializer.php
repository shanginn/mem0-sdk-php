<?php

declare(strict_types=1);

namespace Mem0\Mem0;

use Crell\Serde\SerdeCommon;
use InvalidArgumentException;
use Mem0\Contract\SerializerInterface;
use Mem0\Exception\DeserializationException;
use Mem0\Mem0\Serializer\ChainedNameConverter;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class Serializer implements SerializerInterface
{
    private SerdeCommon $deserializer;
    private SymfonySerializer $serializer;

    public function __construct()
    {
        $encoders    = [new JsonEncoder()];
        $normalizers = [
            new BackedEnumNormalizer(),
            new ObjectNormalizer(
                nameConverter: new ChainedNameConverter([
                    new CamelCaseToSnakeCaseNameConverter(),
                    new MetadataAwareNameConverter(
                        new ClassMetadataFactory(new AttributeLoader())
                    ),
                ]),
                propertyTypeExtractor: new PropertyInfoExtractor(
                    typeExtractors: [new ReflectionExtractor(), new PhpDocExtractor()]
                ),
            ),
            new ArrayDenormalizer(),
        ];
        $this->serializer   = new SymfonySerializer($normalizers, $encoders);
        $this->deserializer = new SerdeCommon();
    }

    public function serialize(mixed $data): string
    {
        $serialized = $this->serializer->serialize(
            data: $data,
            format: 'json',
            context: [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
        );

        return $serialized === '[]' ? '{}' : $serialized;
    }

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
    public function deserialize(string $serialized, string $to, bool $isArray = false): mixed
    {
        if ($isArray) {
            $deserialized = json_decode($serialized, true);

            if (!is_array($deserialized)) {
                throw new InvalidArgumentException('Deserialized data is not an array. Serialized: ' . $serialized);
            }

            $result = [];
            foreach ($deserialized as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = $this->deserialize(json_encode($value), $to);
                }
            }

            return $result;
        }

        return $this->deserializer->deserialize(
            serialized: $serialized,
            from: 'json',
            to: $to
        );
    }
}