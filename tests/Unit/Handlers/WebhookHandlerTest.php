<?php

/** @noinspection PhpUnhandledExceptionInspection */


use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Telegraph;
use DefStudio\Telegraph\Tests\Support\TestWebhookHandler;

it('extracts call data', function () {
    $bot = make_bot();

    app(TestWebhookHandler::class)->handle(webhook_request('test'), $bot);

    expect(TestWebhookHandler::$extracted_data)->toMatchSnapshot();
    expect(TestWebhookHandler::$extracted_data['originalKeyboard']->toArray())->toMatchSnapshot();
});

it('can handle a registered action', function () {
    $bot = make_bot();

    app(TestWebhookHandler::class)->handle(webhook_request('test'), $bot);

    expect(TestWebhookHandler::$calls_count)->toBe(1);
});

it('rejects unregistered actions', function () {
    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request(), $bot);

    Facade::assertRepliedWebhook('Invalid action');
});

it('rejects actions for non public methods', function () {
    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('private_action'), $bot);

    Facade::assertRepliedWebhook('Invalid action');
});

it('can reply', function () {
    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('send_reply'), $bot);

    Facade::assertRepliedWebhook('foo');
});

it('logs webhook calls', function () {
    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('send_reply'), $bot);

    Facade::assertRepliedWebhook('foo');
});

it('can handle a command', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/hello'), $bot);

    Facade::assertSent("Hello!!");
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

it('can change the inline keyboard', function () {
    $bot = make_bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('change_keyboard'), $bot);

    Facade::assertSentData(Telegraph::ENDPOINT_REPLACE_KEYBOARD);
});

it('can delete the inline keyboard', function () {
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
