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

it('can retrieve bot info', function () {
    Telegraph::fake();
    $bot = make_bot();

    $response = Telegraph::bot($bot)->botInfo()->send();
    assertMatchesSnapshot($response->json('result'));
});
