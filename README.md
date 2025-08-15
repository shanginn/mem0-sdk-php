<p align="center">
  <a href="https://github.com/mem0ai/mem0">
    <img src="https://raw.githubusercontent.com/mem0ai/mem0/main/docs/images/banner-sm.png" width="800px" alt="Mem0 - The Memory Layer for Personalized AI">
  </a>
</p>
<p align="center" style="display: flex; justify-content: center; gap: 20px; align-items: center;">
  <a href="https://trendshift.io/repositories/11194" target="blank">
    <img src="https://trendshift.io/api/badge/repositories/11194" alt="mem0ai%2Fmem0 | Trendshift" width="250" height="55"/>
  </a>
</p>

<p align="center">
  <a href="https://mem0.ai">Learn more</a>
  ·
  <a href="https://mem0.dev/DiG">Join Discord</a>
  ·
  <a href="https://mem0.dev/demo">Demo</a>
  ·
  <a href="https://mem0.dev/openmemory">OpenMemory</a>
</p>

<p align="center">
  <a href="https://mem0.dev/DiG">
    <img src="https://dcbadge.vercel.app/api/server/6PzXDgEjG5?style=flat" alt="Mem0 Discord">
  </a>
  <a href="https://github.com/mem0ai/mem0-php"> <!-- Placeholder for PHP SDK repo -->
    <img src="https://img.shields.io/static/v1?label=php-sdk&message=mem0ai&color=blue&style=flat-square" alt="Mem0 PHP SDK">
  </a>
  <a href="https://www.ycombinator.com/companies/mem0">
    <img src="https://img.shields.io/badge/Y%20Combinator-S24-orange?style=flat-square" alt="Y Combinator S24">
  </a>
</p>

<p align="center">
  <a href="https://mem0.ai/research"><strong>📄 Building Production-Ready AI Agents with Scalable Long-Term Memory →</strong></a>
</p>
<p align="center">
  <strong>⚡ +26% Accuracy vs. OpenAI Memory • 🚀 91% Faster • 💰 90% Fewer Tokens</strong>
</p>

## Mem0 PHP SDK

This is the unofficial PHP SDK for [Mem0](https://mem0.ai), the intelligent memory layer for personalized AI. Mem0 enhances AI assistants and agents by enabling them to remember user preferences, adapt to individual needs, and continuously learn over time.

### Key Features & Use Cases

**Core Capabilities:**
- **Multi-Level Memory**: Seamlessly retains User, Session, and Agent state with adaptive personalization.
- **Developer-Friendly**: Intuitive API and cross-platform SDKs.

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
*(Note: Replace `shanginn/mem0` with the actual package name once published on Packagist.)*

### 2. API Key Setup

1.  Sign in to the [Mem0 Platform](https://app.mem0.ai).
2.  Copy your API Key from the dashboard.

### 3. Instantiate Client

Create an instance of the `Mem0` client with your API key:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Mem0\_Request\MemoryOptions;use Mem0\_Request\SearchOptions;use Mem0\DTO\Message;use Mem0\Enum\ApiVersion;use Mem0\Enum\Role;use Mem0\Mem0;

// Retrieve your API key from environment variables or secure storage
$apiKey = getenv('MEM0_API_KEY'); 
if (!$apiKey) {
    die("MEM0_API_KEY environment variable not set.\n");
}

// Optionally, provide organization and project IDs if you have them
// $organizationId = "your_org_id";
// $projectId = "your_project_id";
// $client = new Mem0($apiKey, null, $organizationId, $projectId);

$client = new Mem0($apiKey);

echo "Mem0 PHP Client Initialized!\n";

// Example: Add a memory
try {
    $messages = [
        new Message(Role::USER, "My name is Alex and I live in San Francisco."),
        new Message(Role::ASSISTANT, "Nice to meet you, Alex from San Francisco!"),
    ];
    $options = (new MemoryOptions())->setUserId("user_alex_php");
    
    $createdMemories = $client->add($messages, $options);
    echo "Added memory for user_alex_php. Response:\n";
    foreach ($createdMemories as $memory) {
        echo "- Memory ID: " . $memory->id . ", Content: " . ($memory->data->memory ?? 'N/A') . "\n";
    }

} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error adding memory: " . $e->getMessage() . "\n";
    if ($e instanceof \Mem0\Exception\ApiException && $e->getErrorData()) {
        echo "Error details: " . json_encode($e->getErrorData()) . "\n";
    }
}

// Example: Search memories
try {
    $searchQuery = "Where does Alex live?";
    $searchOptions = (new SearchOptions())
        ->setUserId("user_alex_php")
        ->setApiVersion(ApiVersion::V2); // Use API v2 for advanced filtering if needed

    $searchResults = $client->search($searchQuery, $searchOptions);
    echo "\nSearching for memories related to '{$searchQuery}' for user_alex_php:\n";
    if (count($searchResults) > 0) {
        foreach ($searchResults as $memory) {
            echo "- Memory ID: " . $memory->id . ", Content: " . $memory->memory . ", Score: " . ($memory->score ?? 'N/A') . "\n";
        }
    } else {
        echo "No relevant memories found.\n";
    }

} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error searching memories: " . $e->getMessage() . "\n";
}

?>
```

### 4. Memory Operations

Mem0 provides a simple interface for performing CRUD operations on memory.

#### 4.1 Create Memories

Memories can be associated with a `user_id`, `agent_id`, `app_id`, or `run_id`.

**Long-term memory for a user:**
These memories persist across multiple sessions.

```php
<?php
use Mem0\_Request\MemoryOptions;use Mem0\DTO\Message;use Mem0\Enum\Role;use Mem0\Mem0;

$client = new Mem0('YOUR_API_KEY');

$messages = [
    new Message(Role::USER, "Alex is a vegetarian."),
    new Message(Role::USER, "Alex is allergic to nuts."),
];

$options = (new MemoryOptions())
    ->setUserId("user_alex_php")
    ->setMetadata(['dietary_source' => 'initial_setup']);

try {
    $response = $client->add($messages, $options);
    // $response will be an array of Mem0\Response\Memory objects
    // e.g., [{"memory": "Is a vegetarian", "event": "ADD"}, {"memory": "Is allergic to nuts", "event": "ADD"}]
    print_r($response);
} catch (\Mem0\Exception\Mem0Exception $e) {
    // Handle error
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

**Short-term memory for a user session:**
Use `run_id` for memories that persist only for the duration of a user session.

```php
<?php
// ... (client initialization) ...
$messages = [
    new Message(Role::USER, "I'm planning a trip to Japan next month."),
    new Message(Role::USER, "I'm interested in vegetarian restaurants in Tokyo."),
];
$options = (new MemoryOptions())
    ->setUserId("user_alex_php") // Can be combined with run_id
    ->setRunId("session_12345_php");

$response = $client->add($messages, $options);
// e.g., [{"memory": "Planning a trip to Japan next month", "event": "ADD"}, ...]
print_r($response);
?>
```

**Long-term memory for agents:**
Store memories for AI assistants or agents using `agent_id`.

```php
<?php
// ... (client initialization) ...
$messages = [
    // Typically, agent memories are derived from assistant's responses or explicit agent knowledge
    new Message(Role::ASSISTANT, "I am a helpful travel assistant specializing in Asian destinations."),
];
$options = (new MemoryOptions())->setAgentId("travel_planner_agent_php");

$response = $client->add($messages, $options);
print_r($response);
?>
```

#### 4.2 Search Memories

Retrieve relevant memories using a query. You can filter by `user_id`, `agent_id`, etc.

```php
<?php
// ... (client initialization) ...
use Mem0\_Request\SearchOptions;
use Mem0\Enum\ApiVersion;

$query = "What are Alex's dietary restrictions?";
$options = (new SearchOptions())
    ->setUserId("user_alex_php")
    ->setLimit(5); // Get top 5 relevant memories

// For advanced filtering (e.g., by metadata or combining conditions), use API v2:
// $options->setApiVersion(ApiVersion::V2)
//         ->setFilters([ // See API documentation for V2 filter structure
//             'AND' => [
//                 ['user_id' => 'user_alex_php'],
//                 ['metadata.dietary_source' => 'initial_setup']
//             ]
//         ]);

try {
    $results = $client->search($query, $options);
    // $results is an array of Mem0\Response\Memory objects
    // e.g., [{"id": "...", "memory": "Is a vegetarian. Is allergic to nuts.", "user_id": "user_alex_php", ...}]
    print_r($results);
} catch (\Mem0\Exception\Mem0Exception $e) {
    // Handle error
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.3 Get All Users/Entities

Fetch all unique users, agents, runs, etc., that have memories.

```php
<?php
// ... (client initialization) ...
try {
    $allUsersResponse = $client->users();
    // $allUsersResponse is a Mem0\Response\AllUsers object
    echo "Total entities: " . $allUsersResponse->count . "\n";
    foreach ($allUsersResponse->results as $user) {
        echo "Entity - ID: {$user->id}, Name: {$user->name}, Type: {$user->type}, Memories: {$user->totalMemories}\n";
    }
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.4 Get All Memories

Fetch all memories, optionally filtered by `user_id`, `agent_id`, etc. Supports pagination.

```php
<?php
// ... (client initialization) ...
use Mem0\_Request\MemoryOptions; // Or SearchOptions for v2 filtering

$options = (new MemoryOptions())
    ->setUserId("user_alex_php")
    ->setPage(1)
    ->setPageSize(10);

// To use v2 advanced filtering with getAll:
// $options = (new SearchOptions()) // Use SearchOptions for v2 capabilities
//    ->setUserId("user_alex_php")
//    ->setApiVersion(ApiVersion::V2)
//    ->setFilters(['metadata.source' => 'chat']); // Example v2 filter

try {
    $memories = $client->getAll($options);
    // $memories is an array of Mem0\Response\Memory objects
    print_r($memories);
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.5 Get Specific Memory by ID

Retrieve a single memory by its unique ID.

```php
<?php
// ... (client initialization) ...
$memoryId = "your_memory_id_here"; // Obtain this from an `add` or `search` response

try {
    $memory = $client->get($memoryId);
    // $memory is a Mem0\Response\Memory object
    print_r($memory);
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.6 Memory History

Track how a specific memory has changed over time.

```php
<?php
// ... (client initialization) ...
$memoryId = "your_memory_id_here";

try {
    $history = $client->history($memoryId);
    // $history is an array of Mem0\Response\MemoryHistory objects
    print_r($history);
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.7 Update Memory

Modify the text content of an existing memory.

```php
<?php
// ... (client initialization) ...
$memoryId = "your_memory_id_to_update";
$newText = "Alex now also enjoys fish occasionally.";

try {
    $updatedMemoryArray = $client->update($memoryId, $newText);
    // $updatedMemoryArray contains the updated Mem0\Response\Memory object(s)
    print_r($updatedMemoryArray);
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.8 Delete Memory

Delete a specific memory by its ID or all memories associated with an entity.

```php
<?php
// ... (client initialization) ...
use Mem0\_Request\DeleteAllMemoriesOptions;

// Delete a single memory
$memoryIdToDelete = "your_memory_id_to_delete";
try {
    $response = $client->delete($memoryIdToDelete);
    // $response is a Mem0\Response\SimpleMessageResponse object, e.g., {"message": "Memory deleted successfully"}
    echo $response->message . "\n";
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Delete all memories for a user
$options = (new DeleteAllMemoriesOptions())->setUserId("user_alex_php");
try {
    $response = $client->deleteAll($options);
    echo $response->message . "\n"; // e.g., "Memories deleted successfully!"
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.9 Delete Users/Entities

Delete all memories associated with a specific user, agent, app, or run, or all entities.

```php
<?php
// ... (client initialization) ...
use Mem0\_Request\DeleteUsersOptions;

// Delete a specific user and their memories
$options = (new DeleteUsersOptions())->setUserId("user_to_delete_php");

// To delete all entities (users, agents, etc.) and their memories:
// $options = new DeleteUsersOptions(); 

try {
    $response = $client->deleteUsers($options);
    // $response is a Mem0\Response\SimpleMessageResponse object
    echo $response->message . "\n"; // e.g., "Entity deleted successfully." or "All users, agents, apps and runs deleted."
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.10 Batch Operations

Update or delete multiple memories in a single API call (up to 1000).

```php
<?php
// ... (client initialization) ...
use Mem0\_Request\MemoryUpdateBody;

// Batch Update
$memoriesToUpdate = [
    new MemoryUpdateBody("memory_id_1", "New text for memory 1"),
    new MemoryUpdateBody("memory_id_2", "Updated information for memory 2"),
];
try {
    $response = $client->batchUpdate($memoriesToUpdate);
    echo $response->message . "\n"; // e.g., "Successfully updated 2 memories"
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Batch Delete
$memoryIdsToDelete = ["memory_id_3", "memory_id_4"];
try {
    $response = $client->batchDelete($memoryIdsToDelete);
    echo $response->message . "\n"; // e.g., "Successfully deleted 2 memories"
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.11 Feedback

Provide feedback on memories.

```php
<?php
// ... (client initialization) ...
use Mem0\_Request\FeedbackPayload;
use Mem0\Enum\FeedbackOption;

$payload = new FeedbackPayload(
    memoryId: "memory_id_for_feedback",
    feedback: FeedbackOption::POSITIVE,
    feedbackReason: "This memory was very relevant."
);
try {
    $response = $client->feedback($payload);
    echo $response->message . "\n"; // e.g., "Feedback submitted successfully."
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

#### 4.12 Project Configuration (Instructions & Categories)

Get or update project-level custom instructions and categories.
Requires `organizationId` and `projectId` to be set in the client.

```php
<?php
// Ensure client is initialized with organizationId and projectId for these operations
// $client = new Mem0("YOUR_API_KEY", null, "YOUR_ORG_ID", "YOUR_PROJECT_ID");
use Mem0\_Request\GetProjectOptions;
use Mem0\_Request\PromptUpdatePayload;

// Get Project Details
$options = (new GetProjectOptions())->setFields(['custom_instructions', 'custom_categories']);
try {
    $projectDetails = $client->getProject($options);
    // $projectDetails is a Mem0\Response\Project object
    echo "Custom Instructions: " . ($projectDetails->customInstructions ?? 'N/A') . "\n";
    // print_r($projectDetails->customCategories);
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error getting project: " . $e->getMessage() . "\n";
}

// Update Project Details
$updatePayload = new PromptUpdatePayload(
    customInstructions: "Always respond in a friendly tone.",
    // customCategories: [['name' => 'New Category', 'description' => '...']] // If API supports object structure
    customCategories: ["new_category_1", "new_category_2"]
);
try {
    $updatedProject = $client->updateProject($updatePayload);
    // $updatedProject is a Mem0\Response\Project object
    echo "Updated Custom Instructions: " . ($updatedProject->customInstructions ?? 'N/A') . "\n";
} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Error updating project: " . $e->getMessage() . "\n";
}
?>
```

#### 4.13 Webhooks

Manage webhooks for your project. Requires `projectId` to be set.

```php
<?php
// Ensure client is initialized with projectId for these operations
// $client = new Mem0("YOUR_API_KEY", null, null, "YOUR_PROJECT_ID");

use Mem0\_Request\CreateWebhookPayload;
use Mem0\_Request\UpdateWebhookPayload;
use Mem0\Enum\WebhookEvent;

// Create Webhook
$createPayload = new CreateWebhookPayload(
    eventTypes: [WebhookEvent::MEMORY_ADDED, WebhookEvent::MEMORY_UPDATED],
    name: "My PHP Webhook",
    url: "https://my.service.com/webhook/mem0"
    // projectId can be omitted if client has default, or specified here
);
try {
    $webhook = $client->createWebhook($createPayload);
    // $webhook is a Mem0\Response\Webhook object
    echo "Created Webhook ID: " . $webhook->webhookId . "\n";
    $webhookId = $webhook->webhookId; // Save for later operations

    // Get Webhooks
    $webhooks = $client->getWebhooks(); // Uses client's projectId
    print_r($webhooks);

    // Update Webhook
    if (isset($webhookId)) {
        $updatePayload = new UpdateWebhookPayload(
            name: "My Updated PHP Webhook",
            url: "https://my.new.service.com/webhook/mem0"
            // projectId can be omitted if client has default
        );
        $updateResponse = $client->updateWebhook($webhookId, $updatePayload);
        echo $updateResponse->message . "\n";
    }

    // Delete Webhook
    // if (isset($webhookId)) {
    //     $deleteResponse = $client->deleteWebhook($webhookId);
    //     echo $deleteResponse->message . "\n";
    // }

} catch (\Mem0\Exception\Mem0Exception $e) {
    echo "Webhook Error: " . $e->getMessage() . "\n";
}
?>
```

## 📚 Documentation & Support

-   Full API docs: [https://docs.mem0.ai/api-reference](https://docs.mem0.ai/api-reference)
-   Platform Guide: [https://docs.mem0.ai/platform/guide](https://docs.mem0.ai/platform/guide)
-   Community: [Discord](https://mem0.dev/DiG) · [Twitter](https://x.com/mem0ai)
-   Contact: founders@mem0.ai

## Citation

```bibtex
@article{mem0,
  title={Mem0: Building Production-Ready AI Agents with Scalable Long-Term Memory},
  author={Chhikara, Prateek and Khant, Dev and Aryan, Saket and Singh, Taranjeet and Yadav, Deshraj},
  journal={arXiv preprint arXiv:2504.19413},
  year={2025}
}
```

## ⚖️ License

Apache 2.0 — see the [LICENSE](https://github.com/mem0ai/mem0/blob/main/LICENSE) file for details.