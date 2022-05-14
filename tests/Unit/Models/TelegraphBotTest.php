<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Exceptions\TelegramUpdatesException;
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
    withfakeUrl();

    Telegraph::fake();
    $bot = make_bot();

    $bot->registerWebhook()->send();

    Telegraph::assertRegisteredWebhook();
});

it('can unregister its webhook', function () {
    Telegraph::fake();
    $bot = make_bot();

    $bot->unregisterWebhook()->send();

    Telegraph::assertUnregisteredWebhook();
});

it('can register commands', function () {
    Telegraph::fake();

    $bot = make_bot();

    $bot->registerCommands(['foo' => 'bar'])->send();

    Telegraph::assertSentData(
        \DefStudio\Telegraph\Telegraph::ENDPOINT_REGISTER_BOT_COMMANDS,
        [
           'commands' => [
               ['command' => 'foo', 'description' => 'bar'],
           ],
       ]
    );
});

it('can unregister commands', function () {
    Telegraph::fake();

    $bot = make_bot();

    $bot->unregisterCommands()->send();

    Telegraph::assertSentData(
        \DefStudio\Telegraph\Telegraph::ENDPOINT_UNREGISTER_BOT_COMMANDS,
        []
    );
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

it('can poll for updates', function () {
    Telegraph::fake();

    $bot = make_bot();

    assertMatchesSnapshot($bot->updates()->toArray());
});

it('throws an exception if poll failed', function () {
    Telegraph::fake([
        \DefStudio\Telegraph\Telegraph::ENDPOINT_GET_BOT_UPDATES => [
            'ok' => false,
            'description' => 'foo',
        ],
    ]);

    $bot = make_bot();
    $bot->name = 'Test Bot';

    $bot->updates();
})->throws(TelegramUpdatesException::class, 'annot retrieve updates for Test Bot bot: foo');

it('throws an exception if a webhook is set up', function () {
    Telegraph::fake([
        \DefStudio\Telegraph\Telegraph::ENDPOINT_GET_BOT_UPDATES => [
            'ok' => false,
            'description' => "Conflict: can't use getUpdates method while webhook is active; use deleteWebhook to delete the webhook first",
            'error_code' => 409,
        ],
    ]);

    $bot = make_bot();
    $bot->name = 'Test Bot';
    $bot->id = 42;

    $bot->updates();
})->throws(TelegramUpdatesException::class, 'Cannot retrieve updates for Test Bot bot while a webhook is set. First, delete the webhook with [artisan telegraph:delete-webhook 42] or programmatically calling [$bot->deleteWebhook()]');

it('can store a downloadable file', function () {
    Telegraph::fake();

    $bot = make_bot();

    $bot->store('123456', 'test/bots');

    Telegraph::assertStoredFile('123456');
});
