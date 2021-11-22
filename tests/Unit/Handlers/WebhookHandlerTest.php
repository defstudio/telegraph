<?php

/** @noinspection PhpUnhandledExceptionInspection */


use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Telegraph;
use DefStudio\Telegraph\Tests\Support\TestWebhookHandler;

it('extracts call data', function () {
    $bot = make_bot();

    app(TestWebhookHandler::class)->handle(webhook_request('test'), $bot);

    expect(TestWebhookHandler::$extracted_data)->toMatchSnapshot();
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
