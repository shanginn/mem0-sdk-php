<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mem0\DTO\Memory;
use Mem0\Mem0;
use Phenogram\Framework\TelegramBot;
use Phenogram\Bindings\Types\Interfaces\UpdateInterface;
use Shanginn\Openai\OpenaiSimple;

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..')->load();

$botToken = getenv('TELEGRAM_BOT_TOKEN');
assert(is_string($botToken), 'Bot token must be a string');

$openrouterApiKey = getenv('OPENROUTER_API_KEY');
assert(is_string($openrouterApiKey), 'Anthropic API key must be a string');

$deepseekApiKey = getenv('DEEPSEEK_API_KEY');
assert(is_string($deepseekApiKey), 'DeepSeek API key must be a string');

$mem0ApiKey = getenv('MEM0_API_KEY');
assert(is_string($mem0ApiKey), 'Mem0 API key must be a string');

function toOpenaiMessages(array $messages): array {
    return array_map(function ($message) {
        match ($message['role']) {
            'system' => new \Shanginn\Openai\ChatCompletion\Message\SystemMessage($message['content']),
            'user' => new \Shanginn\Openai\ChatCompletion\Message\UserMessage($message['content']),
            'assistant' => new \Shanginn\Openai\ChatCompletion\Message\AssistantMessage($message['content']),
            default => throw new InvalidArgumentException("Unknown role: {$message['role']}"),
        };
    }, $messages);
}

function toMem0Messages(array $messages): array {
    return array_map(function ($message) {
        return new \Mem0\DTO\Message(
            role: \Mem0\Enum\Role::from($message['role']),
            content: $message['content']
        );
    }, $messages);
}

$openai = OpenaiSimple::create(
    apiKey: $openrouterApiKey,
    model: 'moonshotai/kimi-k2',
    apiUrl: 'https://openrouter.ai/api/v1'
);

$mem0 = new Mem0($mem0ApiKey);

$messages = [];

$handler = function (UpdateInterface $update, TelegramBot $bot) use ($openai, $mem0, $messages) {
    $userId = $update->message->from->id;
    $chatId = $update->message->chat->id;

    // Retrieve relevant memories
    $relevantMemories = $mem0->search(
        query: $update->message->text,
        filters: new \Mem0\DTO\Filter(
            and: [
                ['user_id' => "$chatId-$userId"],
                ['app_id' => 'mem0-test'],
            ],
        )
    );
    dump($relevantMemories);
    if (count($relevantMemories) > 0) {
        $memoriesStr = implode("\n", array_map(
            fn(Memory $entry) => "- {$entry->memory}",
            $relevantMemories
        ));
    } else {
        $memoriesStr = "No relevant memories found.";
    }

    // Generate Assistant response
    $systemPrompt = "You are a helpful AI. Answer the question based on query and memories.\nUser Memories:\n{$memoriesStr}";
    if (!isset($messages[$userId])) {
        $messages[$userId] = [];
    }

    try {
        $response = $openai->generate(
            system: $systemPrompt,
            userMessage: $update->message->text,
            history: toOpenaiMessages($messages[$userId]),
        );

        $messages[$userId][] = ['role' => 'user', 'content' => $update->message->text];
        $messages[$userId][] = ['role' => 'assistant', 'content' => $response];

        $results = $mem0->add(
            messages: toMem0Messages($messages[$userId]),
            agentId: 'mem0-telegram-bot',
            userId: "$chatId-$userId",
            appId: 'mem0-test',
        );

        if (count($results) > 0) {
            $bot->api->sendMessage(
                chatId: $chatId,
                text: "<i>Память обновлена.</i>",
                parseMode: 'HTML'
            );
        }

            // Send response back to Telegram
        $bot->api->sendMessage(
            chatId: $update->message->chat->id,
            text: (string)$response
        );
    } catch (Throwable $e) {
        // Handle errors gracefully
        dump($e);
        $bot->api->sendMessage(
            chatId: $update->message->chat->id,
            text: "An error occurred while processing your request: " . $e->getMessage()
        );
    }

};

$bot = new TelegramBot($botToken);
$bot->addHandler($handler)
    ->supports(fn (UpdateInterface $update) => $update->message?->text !== null);
$bot->run();