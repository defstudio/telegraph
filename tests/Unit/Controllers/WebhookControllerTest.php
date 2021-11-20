<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\LaravelTelegraph\Controllers\WebhookController;
use DefStudio\LaravelTelegraph\Tests\Support\TestWebhookHandler;

test('correct token is required', function () {
    app(WebhookController::class)->handle(webhook_request(), 'wrong');
})->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class);

it('calls configured handler', function () {
    $response = app(WebhookController::class)->handle(webhook_request('test'), "123456AAABBB");

    expect(TestWebhookHandler::$calls_count)->toBe(1);

    expect($response)->toHaveNoContent();
});
