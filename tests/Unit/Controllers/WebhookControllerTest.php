<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Controllers\WebhookController;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Tests\Support\TestWebhookHandler;
use Illuminate\Database\Eloquent\Collection;

it('calls configured handler', function () {
    $bot = bot();
    $bot->setRelation('chats', Collection::make([TelegraphChat::factory(['chat_id' => '-123456789'])->make()]));

    $response = app(WebhookController::class)->handle(webhook_request('test'), $bot->token);

    expect(TestWebhookHandler::$calls_count)->toBe(1);

    expect($response->status())->toBe(204);
});
