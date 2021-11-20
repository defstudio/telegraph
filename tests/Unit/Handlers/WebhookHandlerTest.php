<?php

/** @noinspection PhpUnhandledExceptionInspection */


use DefStudio\LaravelTelegraph\Tests\Support\TestWebhookHandler;

test('can handle a registered action', function () {
    app(TestWebhookHandler::class)->handle(webhook_request('test'));

    expect(TestWebhookHandler::$calls_count)->toBe(1);
});
