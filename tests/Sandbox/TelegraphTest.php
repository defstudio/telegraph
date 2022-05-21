<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */

use DefStudio\Telegraph\DTO\Photo;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Support\Facades\Storage;
use function Spatie\Snapshots\assertMatchesSnapshot;

it('can return bot info', function () {
    $bot = sandbox_bot();

    $response = Telegraph::bot($bot)->botInfo()->send();
    assertMatchesSnapshot($response->json('result'));
})->skip(fn () => empty(env('SANDOBOX_TELEGRAM_BOT_TOKEN')) || env('SANDOBOX_TELEGRAM_BOT_TOKEN') === ':fake_bot_token:', 'Sandbox telegram bot token missing');

it('can store chat files', function () {
    $bot = sandbox_bot();

    Storage::fake();

    $photo = Photo::fromArray([
        "file_id" => "AgACAgQAAxkBAAMtYnZXPgRqZddLGQG6LSvtxQ0cQg4AApi5MRtoC7hTdvog5WT4h4QBAAMCAANzAAMkBA",
        "file_size" => 1514,
        "width" => 90,
        "height" => 84,
    ]);

    Telegraph::bot($bot)
        ->store($photo, Storage::path('images/bot'), 'my_file.jpg');

    expect(Storage::exists('images/bot/my_file.jpg'))->toBeTrue();
})->skip(fn () => empty(env('SANDOBOX_TELEGRAM_BOT_TOKEN')) || env('SANDOBOX_TELEGRAM_BOT_TOKEN') === ':fake_bot_token:', 'Sandbox telegram bot token missing');
