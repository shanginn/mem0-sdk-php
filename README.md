# ⚠️ UNOFFICIAL MEM0 PHP SDK

**This is an unofficial PHP SDK for the Mem0 API. This library is not affiliated with, endorsed by, or officially supported by Mem0.**

---

<p align="center">
  <a href="https://github.com/mem0ai/mem0">
    <img src="https://raw.githubusercontent.com/mem0ai/mem0/main/docs/images/banner-sm.png" width="800px" alt="Mem0 - The Memory Layer for Personalized AI">
  </a>
</p>
<p align="center">
  <strong>⚡ +26% Accuracy vs. OpenAI Memory • 🚀 91% Faster • 💰 90% Fewer Tokens</strong>
</p>

## Mem0 PHP SDK

This is an unofficial PHP SDK for [Mem0](https://mem0.ai), the intelligent memory layer for personalized AI. Mem0 enhances AI assistants and agents by enabling them to remember user preferences, adapt to individual needs, and continuously learn over time.

### Key Features & Use Cases

**Core Capabilities:**
- **Multi-Level Memory**: Seamlessly retains User, Session, and Agent state with adaptive personalization.
- **Developer-Friendly**: Intuitive API and cross-platform SDKs.
- **Type Safety**: Full PHP 8.2+ type declarations and strict typing
- **Async Support**: Built on Amp HTTP client for non-blocking operations
- **Advanced Filtering**: Complex query capabilities with AND/OR logic
- **Rich Memory Types**: Support for conversational memories and metadata

**Applications:**
- **AI Assistants**: Consistent, context-rich conversations.
- **Customer Support**: Recall past tickets and user history for tailored help.
- **Personalized Experiences**: Adapt applications based on user behavior and history.

## 🚀 Quickstart Guide

### 1. Installation

Install the SDK via Composer:

```bash
composer require shanginn/mem0
```

### 2. API Key Setup

1. Sign in to the [Mem0 Platform](https://app.mem0.ai).
2. Copy your API Key from the dashboard.

### 3. Basic Usage

Create an instance of the `Mem0` client with your API key:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Mem0\Mem0;
use Mem0\DTO\Message;
use Mem0\Enum\Role;

// Retrieve your API key from environment variables or secure storage
$apiKey = getenv('MEM0_API_KEY'); 
if (!$apiKey) {
    die("MEM0_API_KEY environment variable not set.\n");
}

// Initialize the client
$mem0 = new Mem0($apiKey);

// Add a simple memory
$response = $mem0->addMemory(
    messages: "Hi, my name is Alice and I love hiking",
    userId: 'user-123'
);

echo "Memory added successfully!\n";
dump($response);
```

## Memory Management

### Adding Memories

The `addMemory()` method supports both simple string messages and complex conversational arrays:

```php
// Simple text memory
$mem0->addMemory(
    messages: "I prefer vegetarian restaurants",
    userId: 'user-123'
);

// Rich conversational memory
$messages = [
    new Message(Role::USER, "What's the weather like?"),
    new Message(Role::ASSISTANT, "It's sunny and 75°F today."),
    new Message(Role::USER, "Perfect for a bike ride!")
];

$response = $mem0->addMemory(
    messages: $messages,
    userId: 'user-123',
    metadata: ['location' => 'San Francisco', 'activity' => 'cycling']
);

// Memory with expiration and custom instructions
$mem0->addMemory(
    messages: "Temporary project preferences",
    userId: 'user-123',
    expirationDate: new DateTime('2024-12-31'),
    customInstructions: "Focus on project-related preferences only",
    immutable: false
);
```

### Retrieving Memories

Use the `listMemories()` method with powerful filtering options:

```php
use Mem0\DTO\Filter;
use Mem0\DTO\FilterOperator;

// Simple retrieval
$memories = $mem0->listMemories();

// Filtered retrieval with complex conditions
$filter = new Filter(
    and: [
        ['user_id' => 'alice'],
        ['created_at' => new FilterOperator(
            gte: '2024-07-01',
            lte: '2024-07-31'
        )]
    ],
    or: [
        ['agent_id' => new FilterOperator(
            in: ['travel-agent', 'food-agent']
        )],
        ['categories' => 'preferences']
    ]
);

$memories = $mem0->listMemories(
    filters: $filter,
    fields: ['id', 'memory', 'created_at', 'metadata'],
    page: 1,
    pageSize: 50
);

foreach ($memories as $memory) {
    echo "Memory: {$memory->memory}\n";
    echo "Created: {$memory->createdAt}\n";
    if ($memory->metadata) {
        echo "Metadata: " . json_encode($memory->metadata) . "\n";
    }
    echo "---\n";
}
```

## Advanced Configuration

### Custom HTTP Client

Implement your own HTTP client for custom behavior:

```php
use Mem0\Contract\ClientInterface;

class CustomClient implements ClientInterface
{
    public function sendRequest(string $method, string $endpoint, string $body = '', array $queryParams = []): string
    {
        // Your custom HTTP implementation
        // Return JSON response as string
    }
}

$mem0 = new Mem0(
    apiKey: 'your-api-key',
    client: new CustomClient()
);
```

### Custom Serialization

Implement custom serialization logic:

```php
use Mem0\Contract\SerializerInterface;

class CustomSerializer implements SerializerInterface
{
    public function serialize(object $data): string
    {
        // Your custom serialization logic
        return json_encode($data);
    }

    public function deserialize(string $json, string $className, bool $isArray = false): mixed
    {
        // Your custom deserialization logic
        $data = json_decode($json, true);
        // ... transform to $className instances
        return $data;
    }
}

$mem0 = new Mem0(
    apiKey: 'your-api-key',
    serializer: new CustomSerializer()
);
```

### Default Organization and Project

Set default organization and project IDs:

```php
$mem0 = new Mem0(
    apiKey: 'your-api-key',
    defaultOrgId: 'org-123',
    defaultProjectId: 'project-456'
);

// These will automatically use the default org/project
$mem0->addMemory(
    messages: "Default scoped memory",
    userId: 'user-123'
);
```

## Data Transfer Objects (DTOs)

The SDK provides rich DTOs for type-safe interactions:

### Message
```php
use Mem0\DTO\Message;
use Mem0\Enum\Role;

$message = new Message(
    role: Role::USER, // or Role::ASSISTANT, Role::SYSTEM
    content: "Hello, world!"
);
```

### Filter & FilterOperator
```php
use Mem0\DTO\Filter;
use Mem0\DTO\FilterOperator;

$filter = new Filter(
    and: [
        ['status' => 'active'],
        ['priority' => new FilterOperator(gte: 5)]
    ],
    or: [
        ['user_id' => new FilterOperator(in: ['user1', 'user2'])],
        ['agent_id' => 'special-agent']
    ]
);
```

### Memory
```php
// Returned from listMemories()
foreach ($memories as $memory) {
    echo "ID: {$memory->id}\n";
    echo "Content: {$memory->memory}\n";
    echo "Created: {$memory->createdAt}\n";
    echo "User ID: {$memory->userId}\n";
    echo "Categories: " . implode(', ', $memory->categories ?? []) . "\n";
    if ($memory->metadata) {
        echo "Metadata: " . json_encode($memory->metadata) . "\n";
    }
}
```

## Error Handling

The SDK throws specific exceptions for different error conditions:

```php
use Mem0\Exception\Mem0ApiException;
use Mem0\Exception\HttpClientException;
use Mem0\Exception\DeserializationException;
use Mem0\Exception\InvalidArgumentException;

try {
    $memories = $mem0->listMemories();
} catch (Mem0ApiException $e) {
    echo "API Error: " . $e->getMessage() . "\n";
    echo "Status Code: " . $e->getStatusCode() . "\n";
    echo "Response Body: " . $e->getResponseBody() . "\n";
} catch (HttpClientException $e) {
    echo "HTTP Client Error: " . $e->getMessage() . "\n";
} catch (DeserializationException $e) {
    echo "Serialization Error: " . $e->getMessage() . "\n";
} catch (InvalidArgumentException $e) {
    echo "Invalid Argument: " . $e->getMessage() . "\n";
}
```
