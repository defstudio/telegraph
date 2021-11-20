<?php

use DefStudio\LaravelTelegraph\Tests\Support\TestWebhookHandler;
use DefStudio\LaravelTelegraph\Tests\TestCase;
use Illuminate\Http\Request;

uses(TestCase::class)->in(__DIR__);

function register_webhook_handler(string $handler = TestWebhookHandler::class): void
{
    if ($handler == TestWebhookHandler::class) {
        TestWebhookHandler::reset();
    }
    config()->set('telegraph.webhook_handler', $handler);
}

function webhook_request($action = 'invalid', $handler = TestWebhookHandler::class): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'callback_query' => [
            'id' => 159753,
            'message' => [
                'message_id' => 123456,
                'chat' => [
                    'id' => 789456,
                ],
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            ["text" => "test", "callback_data" => "action:test;id:1"],
                            ["text" => "delete", "callback_data" => "action:delete;id:2"],
                        ],
                        [
                            ["text" => "👀 Apri", "url" => 'https://test.it'],
                        ],
                    ],
                ],
            ],
            'data' => "action:$action",
        ],
    ]);
}
