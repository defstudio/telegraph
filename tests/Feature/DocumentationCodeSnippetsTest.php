<?php

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph as TelegraphCore;

it('documents the programmatic bot chat and message flow', function () {
    config()->set('services.telegram.bot_token', 'test-bot-token');

    $bot = TelegraphBot::create([
        'token' => config('services.telegram.bot_token'),
        'name' => 'Support Bot',
    ]);

    $chat = $bot->chats()->create([
        'chat_id' => '123456789',
        'name' => 'Personal chat',
    ]);

    Telegraph::fake();

    $chat = TelegraphChat::query()
        ->where('name', 'Personal chat')
        ->firstOrFail();

    $chat->html('<strong>Hello!</strong>')->send();

    expect($bot)
        ->toBeInstanceOf(TelegraphBot::class)
        ->name->toBe('Support Bot')
        ->and($chat)
        ->toBeInstanceOf(TelegraphChat::class)
        ->chat_id->toBe('123456789');

    Telegraph::assertSent('<strong>Hello!</strong>');
    Telegraph::assertSentData(TelegraphCore::ENDPOINT_MESSAGE, [
        'chat_id' => $chat->chat_id,
        'text' => '<strong>Hello!</strong>',
    ]);
});

it('documents handling the start command with a custom webhook handler', function () {
    $bot = TelegraphBot::create([
        'token' => 'test-bot-token',
        'name' => 'Support Bot',
    ]);

    $bot->chats()->create([
        'chat_id' => '-123456789',
        'name' => 'Personal chat',
    ]);

    Telegraph::fake();

    $handler = new class () extends WebhookHandler {
        public function start(): void
        {
            $this->chat->html('Welcome!')->send();
        }
    };

    $handler->handle(webhook_command('/start'), $bot);

    Telegraph::assertSent('Welcome!');
    Telegraph::assertSentData(TelegraphCore::ENDPOINT_MESSAGE, [
        'chat_id' => '-123456789',
        'text' => 'Welcome!',
    ]);
});
