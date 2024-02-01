<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\InlineQueryResultGif;
use DefStudio\Telegraph\Exceptions\TelegramUpdatesException;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Keyboard\Keyboard;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;

use function Spatie\Snapshots\assertMatchesSnapshot;

uses(LazilyRefreshDatabase::class);

test('name is set to ID if missing', function () {
    $bot = TelegraphBot::create([
        'token' => Str::uuid(),
    ]);

    expect($bot->name)->toBe("Bot #$bot->id");
});

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

it('can get registered commands', function () {
    Telegraph::fake();

    $bot = make_bot();

    $bot->getRegisteredCommands()->send();

    Telegraph::assertSentData(
        \DefStudio\Telegraph\Telegraph::ENDPOINT_GET_REGISTERED_BOT_COMMANDS,
        []
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

it('can answer to an inline query', function () {
    Telegraph::fake();

    $bot = make_bot();

    $bot->answerInlineQuery("a99", [
        InlineQueryResultGif::make(99, 'https://gif.dev', 'https://thumb.gif.test')
            ->caption('foo')
            ->title('bar')
            ->duration(200)
            ->height(400)
            ->width(300)
            ->keyboard(Keyboard::make()->button('buy')->action('buy')->param('id', 99)),
        InlineQueryResultGif::make(98, 'https://gif2.dev', 'https://thumb.gif2.test')
            ->caption('baz')
            ->title('quz')
            ->duration(1200)
            ->height(1400)
            ->width(1300)
            ->keyboard(Keyboard::make()->button('buy')->action('buy')->param('id', 98)),

    ])->send();

    Facade::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_ANSWER_INLINE_QUERY, [
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
