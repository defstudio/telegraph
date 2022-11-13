<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Exceptions\TelegramWebhookException;
use DefStudio\Telegraph\Telegraph;

it('can register a webhook', function () {
    withfakeUrl();
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->registerWebhook())
        ->toMatchTelegramSnapshot();
});

it('can register a webhook with a custom domain', function () {
    withfakeUrl();

    config()->set('custom_webhook_domain', 'http://foo.bar.baz');

    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->registerWebhook())
        ->toMatchTelegramSnapshot();
});

it('requires an https url to register a webhook', function () {
    \DefStudio\Telegraph\Facades\Telegraph::bot(make_bot())->registerWebhook();
})->throws(TelegramWebhookException::class, 'You application must have a secure (https) url in order to accept webhook calls');

it('can unregister a webhook', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->unregisterWebhook())
        ->toMatchTelegramSnapshot();
});

it('can unregister a webhook dropping all pending updates', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->unregisterWebhook(true))
        ->toMatchTelegramSnapshot();
});

it('can get webhook debug info', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->getWebhookDebugInfo())
        ->toMatchTelegramSnapshot();
});

it('can reply to a webhook call', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->replyWebhook(2123456, 'foo'))
        ->toMatchTelegramSnapshot();
});
