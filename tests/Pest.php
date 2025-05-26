<?php

/** @noinspection PhpUndefinedMethodInspection */

/** @noinspection LaravelFunctionsInspection */

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use DefStudio\Telegraph\Tests\Support\TestWebhookHandler;
use DefStudio\Telegraph\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

uses(TestCase::class)->in("Feature");
uses(TestCase::class)->in("Unit");
uses(TestCase::class)->in("Regression");
uses(TestCase::class)->group('sandbox')->in("Sandbox");

function withfakeUrl(): string
{
    $url = 'https://testbot.defstudio.dev';
    URL::forceScheme('https');
    URL::forceRootUrl($url);

    return $url;
}

/**
 * @return Collection<TelegraphBot>
 */
function bots(int $count): Collection
{
    return TelegraphBot::factory()->count($count)->create();
}

function sandbox_bot(): TelegraphBot
{
    return bot(env('SANDOBOX_TELEGRAM_BOT_TOKEN'), env('SANDBOX_TELEGRAM_CHAT_ID'));
}

function bot(string $token = '3f3814e1-5836-3d77-904e-60f64b15df36', string $chatId = '-123456789'): TelegraphBot
{
    /** @var TelegraphBot $bot */
    $bot = TelegraphBot::factory(['token' => $token])
        ->create();

    $bot->chats()->save(TelegraphChat::factory(['chat_id' => $chatId, 'telegraph_bot_id' => null])->make());

    return $bot->refresh();
}

function make_bot(): TelegraphBot
{
    $bot = TelegraphBot::factory(['token' => '3f3814e1-5836-3d77-904e-60f64b15df36'])->make();

    $chat = TelegraphChat::factory(['chat_id' => '-123456789'])->make();

    $bot->setRelation('chats', Collection::make([$chat]));

    return $bot;
}

function chat(): TelegraphChat
{
    return TelegraphChat::factory(['chat_id' => '-123456789'])->create();
}

function make_chat(): TelegraphChat
{
    $bot = make_bot();
    $chat = TelegraphChat::factory([
        'chat_id' => '-123456789',
        'telegraph_bot_id' => null,
    ])->make();

    $chat->setRelation('bot', $bot);
    $bot->setRelation('chats', Collection::make([$chat]));

    return $chat;
}

function register_webhook_handler(string $handler = TestWebhookHandler::class): void
{
    if ($handler == TestWebhookHandler::class) {
        TestWebhookHandler::reset();
    }
    config()->set('telegraph.webhook.handler', $handler);
}


function webhook_message($handler = TestWebhookHandler::class, array $message = null): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'message' => $message ?? [
                'message_id' => 123456,
                'chat' => [
                    'id' => 123456,
                    'type' => 'group',
                    'title' => 'Test chat',
                ],
                "text" => 'foo',
            ],
    ]);
}


function webhook_message_reaction($handler = TestWebhookHandler::class, array $message = null): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'message_reaction' => $message ?? [
                'chat' => [
                    'id' => 3,
                    'type' => 'a',
                    'title' => 'b',
                ],
                'date' => 1727211008,
                'user' => [
                    'id' => 1,
                    'first_name' => 'a',
                ],
                'message_id' => 2,
                'new_reaction' => [
                    [
                        'type' => 'emoji',
                        'emoji' => '👍',
                    ],
                ],
                'old_reaction' => [],
            ],
    ]);
}

function webhook_request($action = 'invalid', $handler = TestWebhookHandler::class, int $chat_id = -123456789): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'callback_query' => [
            'id' => 159753,
            'from' => [
                'id' => 8974,
                'is_bot' => true,
                'first_name' => 'Test Bot',
                'username' => 'test_bot',
                'can_join_groups' => true,
                'can_read_all_group_messages' => false,
                'supports_inline_queries' => false,
            ],
            'message' => [
                'message_id' => 123456,
                'chat' => [
                    'id' => $chat_id,
                    'type' => 'group',
                    'title' => 'Test chat',
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
                'date' => 1646516736,
            ],
            'data' => "action:$action",
        ],
    ]);
}

function webhook_command($command, $handler = TestWebhookHandler::class, int $chat_id = -123456789): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'message' => [
            'message_id' => 123456,
            'chat' => [
                'id' => $chat_id,
                'type' => 'private',
                'username' => 'john-smith',
            ],
            'text' => $command,
            'date' => 1646516736,
        ],
    ]);
}

function webhook_inline_query($handler = TestWebhookHandler::class): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'inline_query' => [
            'id' => "a99",
            'query' => 'foo',
            'from' => [
                'id' => 1,
                'is_bot' => false,
                'first_name' => 'e',
                'last_name' => 'f',
                'username' => 'g',
            ],
            'offset' => '+4',
            'chat_type' => 'private',
        ],
    ]);
}

function webhook_pre_checkout_query($handler = TestWebhookHandler::class): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'pre_checkout_query' => [
            'id' => 3,
            'from' => [
                'id' => 1,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
            ],
            'currency' => 'EUR',
            'total_amount' => '100',
            'invoice_payload' => 'test payload',
            'shipping_option_id' => 'test id',
            'order_info' => [
                'name' => 'test name',
                'phone_number' => '+39 333 333 3333',
                'email' => 'test@email.it',
                'shipping_address' => [
                    'country_code' => '+39',
                    'state' => 'italy',
                    'city' => 'rome',
                    'street_line1' => 'street test',
                    'street_line2' => '',
                    'post_code' => '00042',
                ],
            ],
        ],
    ]);
}

function webhook_successful_payment($handler = TestWebhookHandler::class, int $chat_id = -123456789): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'message' => [
            "message_id" => 1,
            "from" => [
                "id" => 100,
                "is_bot" => false,
                "username" => "Test User",
                "last_name" => "Test User",
                "first_name" => "Test User",
                "is_premium" => true,
                "language_code" => "it",
            ],
            "chat" => [
                "id" => $chat_id,
                "type" => "a",
                "title" => "b",
                "all_members_are_administrators" => false,
            ],
            "date" => 100000,
            'successful_payment' => [
                'currency' => 'EUR',
                'total_amount' => 100,
                'invoice_payload' => 'id_10',
                'subscription_expiration_date' => 14000,
                'is_recurring' => false,
                'is_first_recurring' => false,
                'shipping_option_id' => 10,
                'order_info' => [
                    'name' => 'test name',
                    'phone_number' => ' + 39 333 333 3333',
                    'email' => 'test@email . it',
                    'shipping_address' => [
                        'country_code' => ' + 39',
                        'state' => 'italy',
                        'city' => 'rome',
                        'street_line1' => 'street test',
                        'street_line2' => '',
                        'post_code' => '00042',
                    ],
                ],
                'telegram_payment_charge_id' => 10,
                'provider_payment_charge_id' => 10,
            ],
        ],
    ]);
}

function webhook_bot_chat_status_update($handler = TestWebhookHandler::class): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'update_id' => 123456789,
        'my_chat_member' => [
            'chat' => [
                'id' => 123456789,
                'first_name' => 'Mario',
                'type' => 'private',
            ],
            'from' => [
                'id' => 123456789,
                'is_bot' => false,
                'first_name' => 'Mario',
                'language_code' => 'it',
            ],
            'date' => 123456789,
            'old_chat_member' => [
                'user' => [
                    'id' => 1111111,
                    'is_bot' => true,
                    'first_name' => 'Bot',
                    'username' => 'MarioBot',
                ],
                'status' => 'member',
            ],
            'new_chat_member' => [
                'user' => [
                    'id' => 2222222,
                    'is_bot' => true,
                    'first_name' => 'Bot',
                    'username' => 'MarioBot',
                ],
                'status' => 'kicked',
                'until_date' => 0,
            ],
        ],
    ]);
}

function webhook_migrate_to_chat($handler = TestWebhookHandler::class, int $chat_id = -123456789, int $new_chat_id = 20): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'message' => [
            "chat" => [
                "id" => $chat_id,
                "type" => "a",
                "title" => "b",
                "all_members_are_administrators" => false,
            ],
            "date" => 100000,
            "from" => [
                "id" => 100,
                "is_bot" => false,
                "username" => "Test User",
                "last_name" => "Test User",
                "first_name" => "Test User",
                "is_premium" => true,
                "language_code" => "it",
            ],
            "message_id" => 1,
            "migrate_to_chat_id" => $new_chat_id,
        ],
    ]);
}

function webhook_chat_join_request($handler = TestWebhookHandler::class, int $chat_id = -123456789, int $user_id = 1): Request
{
    register_webhook_handler($handler);

    return Request::create('', 'POST', [
        'chat_join_request' => [
            'user_chat_id' => $user_id,
            'bio' => 'bio',
            'date' => now()->timestamp,
            'invite_link' => [
                'invite_link' => 'https:/t.me/+EEEEEEE...',
                'creator' => [
                    'id' => 1,
                    'is_bot' => false,
                    'first_name' => 'aa',
                    'last_name' => 'bb',
                    'username' => 'cc',
                    'language_code' => 'dd',
                    'is_premium' => false,
                ],
                'creates_join_request' => true,
                'is_primary' => false,
                'is_revoked' => false,
            ],
            'chat' => [
                'id' => $chat_id,
                'type' => 'a',
                'title' => 'b',
            ],
            'from' => [
                'id' => $user_id,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
                'language_code' => 'd',
                'is_premium' => false,
            ],
        ],
    ]);
}


expect()->extend('toMatchTelegramSnapshot', function () {
    /** @var Closure(Telegraph): Telegraph $configurationClosure */
    $configurationClosure = $this->value;

    /** @var Telegraph $telegraph */
    $telegraph = app(Telegraph::class)->chat(make_chat());

    $telegraph = $configurationClosure($telegraph);

    expect($telegraph->toArray())->toMatchSnapshot();
});

expect()->extend('toMatchUtf8TelegramSnapshot', function () {
    /** @var Closure(Telegraph): Telegraph $configurationClosure */
    $configurationClosure = $this->value;

    /** @var Telegraph $telegraph */
    $telegraph = app(Telegraph::class)->chat(make_chat());

    $telegraph = $configurationClosure($telegraph);

    expect(json_encode(mb_convert_encoding($telegraph->toArray(), 'UTF-8', 'UTF-8')))->toMatchSnapshot();
});
