<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */


use DefStudio\Telegraph\Facades\Telegraph;
use function Spatie\Snapshots\assertMatchesSnapshot;

it('can customize the destination bot', function () {
    $bot = make_bot();
    $telegraph = Telegraph::bot($bot)
        ->registerWebhook();

    expect($telegraph->getUrl())->toStartWith("https://api.telegram.org/bot$bot->token/");
});

it('can customize the destination chat', function () {
    $url = Telegraph::chat(make_chat())
        ->html('foobar')
        ->getUrl();

    expect($url)->toMatchSnapshot();
});

it('can return bot info', function () {
    $bot = bot(env('SANDOBOX_TELEGRAM_BOT_TOKEN'));

    assertMatchesSnapshot($bot->info());
})->skip(fn () => env('SANDOBOX_TELEGRAM_BOT_TOKEN') === ':fake_bot_token:', 'Sandbox telegram bot token missing');
