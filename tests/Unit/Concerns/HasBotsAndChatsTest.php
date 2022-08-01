<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */


use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Facades\Telegraph;

use function Spatie\Snapshots\assertMatchesSnapshot;

it('can customize the destination bot', function () {
    withfakeUrl();
    $bot = make_bot();

    $telegraph = Telegraph::bot($bot)
        ->registerWebhook();

    expect($telegraph->getApiUrl())->toStartWith("https://api.telegram.org/bot$bot->token/");
});

it('can customize the destination chat', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->html('foobar');
    })->toMatchTelegramSnapshot();
});

it('can retrieve bot info', function () {
    Telegraph::fake();
    $bot = make_bot();

    $response = Telegraph::bot($bot)->botInfo()->send();
    assertMatchesSnapshot($response->json('result'));
});

it('can register commands', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->bot(make_bot())->registerBotCommands([
            'foo' => 'first command',
            'bar' => 'second command',
        ]);
    })->toMatchTelegramSnapshot();
});

it('can unregister commands', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->bot(make_bot())->unregisterBotCommands();
    })->toMatchTelegramSnapshot();
});

it('can send a chat action', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->chatAction(ChatActions::TYPING);
    })->toMatchTelegramSnapshot();
});
