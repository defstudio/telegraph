<?php

/** @noinspection LaravelFunctionsInspection */

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Storage;
use function Spatie\Snapshots\assertMatchesSnapshot;

it('can return bot info', function () {
    $bot = sandbox_bot();

    $response = Telegraph::bot($bot)->botInfo()->send();
    assertMatchesSnapshot($response->json('result'));
})->skip(fn () => env('SANDOBOX_TELEGRAM_BOT_TOKEN') === ':fake_bot_token:', 'Sandbox telegram bot token missing');

test('a', function () {
    sandbox_bot();


    $result = Telegraph::document(Storage::path('test.txt'))
        ->keyboard(fn (Keyboard $keyboard) => $keyboard->button('test')->url('www.google.it'))
        ->send();

    // dd($result->body());
})->only();
