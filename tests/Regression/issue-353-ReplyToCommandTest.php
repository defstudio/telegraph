<?php


use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Tests\Support\TestWebhookHandler;

it('can reply to a command', function () {
    $bot = bot();
    Facade::fake();

    app(TestWebhookHandler::class)->handle(webhook_command('/reply_to_command'), $bot);

    Facade::assertSent("foo");
});
