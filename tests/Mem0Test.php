<?php

declare(strict_types=1);

namespace Tests;

use Mem0\Contract\ClientInterface;
use Mem0\DTO\AddMemoryResponseItem;
use Mem0\DTO\Filter;
use Mem0\DTO\FilterOperator;
use Mem0\Mem0;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
class Mem0Test extends TestCase
{
    use MockeryPHPUnitIntegration;

    private MockInterface|ClientInterface $mockClient;
    private Mem0 $mem0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(ClientInterface::class);

        $apiKey = getenv('MEM0_API_KEY');
        if (!$apiKey) {
            $this->markTestSkipped('MEM0_API_KEY environment variable is not set.');
        }

        $this->mem0 = new Mem0(
            apiKey: $apiKey
        );
    }

    public function testListMemories(): void
    {
        $filters = new Filter(
            and: [
                ['user_id' => 'alex'],
                ['created_at' => new FilterOperator(
                    gte: '2024-07-01',
                    lte: '2024-07-31',
                )]
            ],
            or: [
                ['user_id' => 'alice'],
                ['agent_id' => new FilterOperator(
                    in: ['travel-agent', 'sports-agent'],
                )],
            ],
        );

        $memories = $this->mem0->listMemories(
            filters: $filters
        );

        self::assertIsArray($memories);
    }

    public function testAddMemory(): void
    {
        $name = self::fake()->name();
        $response = $this->mem0->addMemory(
            messages: "Hi, my name is $name",
            userId: self::fake()->uuid(),
        );

        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertInstanceOf(AddMemoryResponseItem::class, $response[0]);
    }
}