<?php

/** @noinspection PhpUnhandledExceptionInspection */


use DefStudio\LaravelTelegraph\Facades\LaravelTelegraph as Facade;
use DefStudio\LaravelTelegraph\LaravelTelegraph;
use DefStudio\LaravelTelegraph\Tests\Support\TestWebhookHandler;

it('extracts call data', function () {
    app(TestWebhookHandler::class)->handle(webhook_request('test'));

    expect(TestWebhookHandler::$extracted_data)->toMatchSnapshot();
});

it('can handle a registered action', function () {
    app(TestWebhookHandler::class)->handle(webhook_request('test'));

    expect(TestWebhookHandler::$calls_count)->toBe(1);
});

it('rejects unregistered actions', function () {
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request());

    Facade::assertRepliedWebhook('Invalid action');
});

it('rejects actions for non public methods', function () {
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('private_action'));

    Facade::assertRepliedWebhook('Invalid action');
});

it('can reply', function () {
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('send_reply'));

    Facade::assertRepliedWebhook('foo');
});

it('can change the inline keyboard', function () {
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('change_keyboard'));

    Facade::assertSentData(LaravelTelegraph::ENDPOINT_REPLACE_KEYBOARD);
});

it('can delete the inline keyboard', function () {
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_request('delete_keyboard'));

    Facade::assertSentData(LaravelTelegraph::ENDPOINT_REPLACE_KEYBOARD);
});
