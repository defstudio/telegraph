<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Facades\Telegraph;
use function Spatie\Snapshots\assertMatchesSnapshot;

it('can retrieve its telegram info', function () {
    Telegraph::fake();
    $bot = make_bot();

    assertMatchesSnapshot($bot->info());
});

it('can retrieve its url', function () {
    Telegraph::fake();
    $bot = make_bot();

    assertMatchesSnapshot($bot->url());
});

it('can register its webhook', function () {
    Telegraph::fake();
    $bot = make_bot();

    $bot->registerWebhook()->send();

    Telegraph::assertRegisteredWebhook();
});

it('can get its webhook debug info', function () {
    Telegraph::fake();
    $bot = make_bot();

    $bot->getWebhookDebugInfo()->send();

    Telegraph::assertRequestedWebhookDebugInfo();
});

it('can reply a webhook call', function () {
    Telegraph::fake();
    $bot = make_bot();

    $bot->replyWebhook(1231456, 'hello')->send();

    Telegraph::assertRepliedWebhook('hello');
});
