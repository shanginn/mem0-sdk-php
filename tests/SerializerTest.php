<?php

declare(strict_types=1);

namespace Tests;

use Mem0\DTO\Memory;
use Mem0\Mem0\Serializer;

class SerializerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new Serializer();
    }

    public function testDeserializeMemoriesListResponse(): void
    {
        $json = '[{"id":"226c9133-d2d6-4800-8097-1069677a769f","memory":"User name is Macie White","user_id":"24a10c8d-37df-3da2-8269-f7d6235eda78","metadata":null,"categories":["personal_details"],"created_at":"2025-05-22T00:42:37.712216-07:00","updated_at":"2025-05-22T00:42:37.739243-07:00","expiration_date":null,"internal_metadata":null,"deleted_at":null},{"id":"72ab0c11-6b64-4cc3-8b82-e4ace42553bd","memory":"Is travelling to San Francisco","user_id":"a98306ba-951a-3436-9307-3e249d580c7e","metadata":null,"categories":["travel"],"created_at":"2025-05-22T00:40:55.933009-07:00","updated_at":"2025-05-22T00:40:55.952221-07:00","expiration_date":null,"internal_metadata":null,"deleted_at":null}]';

        $result = $this->serializer->deserialize(
            $json,
            Memory::class,
            true
        );

        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertInstanceOf(Memory::class, $result[0]);
        self::assertInstanceOf(Memory::class, $result[1]);
        self::assertEquals('226c9133-d2d6-4800-8097-1069677a769f', $result[0]->id);
        self::assertEquals('72ab0c11-6b64-4cc3-8b82-e4ace42553bd', $result[1]->id);
        self::assertEquals('User name is Macie White', $result[0]->memory);
    }
}