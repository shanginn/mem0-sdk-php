# Telegram Bot with Memory

A Telegram bot that uses Mem0 to remember past conversations and provide context-aware responses.

## Features

- Maintains conversation memory using Mem0
- AI-powered responses using OpenRouter/DeepSeek
- Context-aware replies based on conversation history
- Per-user memory isolation

## Prerequisites

- PHP 8.4 or higher
- Composer
- Telegram Bot Token
- Mem0 API Key
- OpenRouter API Key (or DeepSeek API Key)

## Installation

1. Install dependencies:
   ```bash
   composer install
   ```

2. Create a `.env` file in the project root with your API keys:
   ```env
   TELEGRAM_BOT_TOKEN=your_telegram_bot_token
   MEM0_API_KEY=your_mem0_api_key
   OPENROUTER_API_KEY=your_openrouter_api_key
   DEEPSEEK_API_KEY=your_deepseek_api_key
   ```

## Setup

### 1. Create a Telegram Bot

1. Message [@BotFather](https://t.me/BotFather) on Telegram
2. Use `/newbot` command and follow the instructions
3. Save the bot token for the `.env` file

### 2. Get Mem0 API Key

1. Sign up at [Mem0](https://mem0.ai)
2. Get your API key from the dashboard

### 3. Get AI Provider API Key

Choose one of:
- **OpenRouter**: Sign up at [OpenRouter](https://openrouter.ai) for access to various AI models
- **DeepSeek**: Sign up at [DeepSeek](https://deepseek.com) for their AI models

## Running the Bot

```bash
php src/bot.php
```

The bot will start polling for messages. Send a message to your bot on Telegram to test it.

## How it Works

1. **Memory Retrieval**: When a user sends a message, the bot searches for relevant memories using Mem0
2. **Context Generation**: Found memories are included in the system prompt to provide context
3. **AI Response**: The AI generates a response based on the current message and retrieved memories
4. **Memory Storage**: The conversation (both user message and AI response) is stored in Mem0 for future reference

## Configuration

The bot uses the following default settings:
- **AI Model**: `moonshotai/kimi-k2` via OpenRouter
- **App ID**: `mem0-test`
- **Agent ID**: `mem0-telegram-bot`

You can modify these in `src/bot.php` as needed.