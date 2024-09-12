<?php

/** @noinspection PhpUnhandledExceptionInspection */


use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Telegraph;
use DefStudio\Telegraph\Tests\Support\TestWebhookHandler;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('rejects unknown chat queries', function () {
    $bot = make_bot();

    app(TestWebhookHandler::class)->handle(webhook_request('test'), $bot);
})->throws(NotFoundHttpException::class);

it('can handle known chat queries', function () {
    $chat = chat();

    app(TestWebhookHandler::class)->handle(webhook_request('test'), $chat->bot);

    expect(TestWebhookHandler::$calls_count)->toBe(1);
});

it('can save unknown chats sending queries', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);

    $bot = bot();

    app(TestWebhookHandler::class)->handle(webhook_request('test', chat_id: 99), $bot);

    expect($bot->chats()->count())->toBe(1);

    Config::set('telegraph.security.store_unknown_chats_in_db', true);

    app(TestWebhookHandler::class)->handle(webhook_request('test', chat_id: 99), $bot);

    expect($bot)
        ->chats->count()->toBe(2)
        ->chats->last()->name->toBe('[group] Test chat');
});

it('extracts call data', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();

    app(TestWebhookHandler::class)->handle(webhook_request('test'), $bot);

    expect(TestWebhookHandler::$extracted_data)->toMatchSnapshot();
    expect(TestWebhookHandler::$extracted_data['originalKeyboard']->toArray())->toMatchSnapshot();
});

it('can handle a registered action', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();

    app(TestWebhookHandler::class)->handle(webhook_request('test'), $bot);

    expect(TestWebhookHandler::$calls_count)->toBe(1);
});

it('can handle a registered action with parameters', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('param_injection'), $bot);

    Facade::assertSent("Foo is [not set]");

    app(TestWebhookHandler::class)->handle(webhook_request('param_injection;foo:bar'), $bot);

    Facade::assertSent("Foo is [bar]");
});

it('rejects unregistered actions', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request(), $bot);

    Facade::assertRepliedWebhook('Invalid action');
});

it('rejects actions for non public methods', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('private_action'), $bot);

    Facade::assertRepliedWebhook('Invalid action');
});

it('can reply', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('send_reply'), $bot);

    Facade::assertRepliedWebhook('foo');
});

it('logs webhook calls', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('send_reply'), $bot);

    Facade::assertRepliedWebhook('foo');
});

it('rejects unknown chats commands', function () {
    $bot = make_bot();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello'), $bot);
})->throws(NotFoundHttpException::class);

it('can handle known chat commands', function () {
    $chat = chat();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello'), $chat->bot);

    Facade::assertSent("Hello!!");
});

it('can save unknown chats sending commands', function () {
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = bot();

    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello', chat_id: 99), $bot);

    expect($bot->chats()->count())->toBe(1);

    Config::set('telegraph.security.store_unknown_chats_in_db', true);

    app(TestWebhookHandler::class)->handle(webhook_command('/hello', chat_id: 99), $bot);

    expect($bot)
        ->chats->count()->toBe(2)
        ->chats->last()->name->toBe('[private] john-smith');
});

it('can handle a command', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello'), $bot);

    Facade::assertSent("Hello!!");
});

it('can handle an unknown command', function () {
    TestWebhookHandler::$handleUnknownCommands = true;

    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/foo'), $bot);

    Facade::assertSent("I can't understand your command: /foo");

    TestWebhookHandler::$handleUnknownCommands = false;
});

it('can handle a command with bot reference', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello@bot'), $bot);

    Facade::assertSent("Hello!!");
});

it('can handle a command with parameters', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello foo'), $bot);

    Facade::assertSent("Hello!! your parameter is [foo]");
});

it('can handle a command with parameters and bot reference', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello@bot foo bot'), $bot);

    Facade::assertSent("Hello!! your parameter is [foo bot]");
});

it('can handle a command with custom start char', function () {
    Config::set('telegraph.commands.start_with', ['-', '=', '!', ' % ', 1]);

    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello@bot foo bot /'), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('-hello@bot foo bot -'), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('=hello@bot foo bot ='), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('!hello@bot foo bot !'), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('%hello@bot foo bot %'), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('1hello@bot foo bot 1'), $bot);

    Facade::assertSent("Hello!! your parameter is [foo bot /]");
    Facade::assertSent("Hello!! your parameter is [foo bot -]");
    Facade::assertSent("Hello!! your parameter is [foo bot =]");
    Facade::assertSent("Hello!! your parameter is [foo bot !]");
    Facade::assertSent("Hello!! your parameter is [foo bot %]");
    Facade::assertSent("Hello!! your parameter is [foo bot 1]");
});

it('cannot handle a command with custom start char', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello@bot foo bot /'), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('-hello@bot foo bot -'), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('=hello@bot foo bot ='), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('!hello@bot foo bot !'), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('%hello@bot foo bot %'), $bot);
    app(TestWebhookHandler::class)->handle(webhook_command('1hello@bot foo bot 1'), $bot);

    Facade::assertSent("Hello!! your parameter is [foo bot /]");

    Facade::assertNotSent("Hello!! your parameter is [foo bot -]");
    Facade::assertNotSent("Hello!! your parameter is [foo bot =]");
    Facade::assertNotSent("Hello!! your parameter is [foo bot !]");
    Facade::assertNotSent("Hello!! your parameter is [foo bot %]");
    Facade::assertNotSent("Hello!! your parameter is [foo bot 1]");
});

it('can change the inline keyboard', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('change_keyboard'), $bot);

    Facade::assertSentData(Telegraph::ENDPOINT_REPLACE_KEYBOARD);
});

it('can delete the inline keyboard', function () {
    Config::set('telegraph.security.allow_callback_queries_from_unknown_chats', true);
    Config::set('telegraph.security.allow_messages_from_unknown_chats', true);

    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('delete_keyboard'), $bot);

    Facade::assertSentData(Telegraph::ENDPOINT_REPLACE_KEYBOARD);
});

it('can handle an inlineQuery', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_inline_query(), $bot);

    Facade::assertSentData(Telegraph::ENDPOINT_ANSWER_INLINE_QUERY, [
        "inline_query_id" => "a99",
        "results" => [
            [
                "gif_url" => "https://gif.dev",
                "thumb_url" => "https://thumb.gif.test",
                "gif_width" => 300,
                "gif_height" => 400,
                "gif_duration" => 200,
                "title" => "bar",
                "caption" => "foo",
                'parse_mode' => 'html',
                "id" => "99",
                "type" => "gif",
                "reply_markup" => [
                    "inline_keyboard" => [
                        [
                            [
                                "text" => "buy",
                                "callback_data" => "action:buy;id:99",
                            ],
                        ],
                    ],
                ],
            ],
            [
                "gif_url" => "https://gif2.dev",
                "thumb_url" => "https://thumb.gif2.test",
                "gif_width" => 1300,
                "gif_height" => 1400,
                "gif_duration" => 1200,
                "title" => "quz",
                "caption" => "baz",
                'parse_mode' => 'html',
                "id" => "98",
                "type" => "gif",
                "reply_markup" => [
                    "inline_keyboard" => [
                        [
                            [
                                "text" => "buy",
                                "callback_data" => "action:buy;id:98",
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]);
});

it('can handle message', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_message(message: [
        'message_id' => 123456,
        'chat' => [
            'id' => -123456789,
            'type' => 'group',
            'title' => 'Test chat',
        ],
        'date' => 1646516736,
        'text' => 'foo',
    ]), $bot);

    Facade::assertSent("Received: foo");
});

it('can handle a member join', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_message(message: [
        'message_id' => 123456,
        'chat' => [
            'id' => -123456789,
            'type' => 'group',
            'title' => 'Test chat',
        ],
        'date' => 1646516736,
        'new_chat_members' => [
            [
                'id' => 123457,
                'is_bot' => 'false',
                'first_name' => 'Bob',
            ],
        ],
    ]), $bot);

    Facade::assertSent("Welcome Bob");
});

it('can handle a member left', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_message(message: [
        'message_id' => 123456,
        'chat' => [
            'id' => -123456789,
            'type' => 'group',
            'title' => 'Test chat',
        ],
        'date' => 1646516736,
        'left_chat_member' => [
            'id' => 123457,
            'is_bot' => 'false',
            'first_name' => 'Bob',
        ],
    ]), $bot);

    Facade::assertSent("Bob just left");
});

it('does not crash on errors', function () {
    $chat = chat();

    Facade::fake();

    app(TestWebhookHandler::class)
        ->handle(webhook_request('trigger_failure'), $chat->bot)
    ;

    Facade::assertRepliedWebhook('Sorry, an error occurred');
});
