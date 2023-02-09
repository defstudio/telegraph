<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */

use DefStudio\Telegraph\Telegraph;

it('can retrieve bot commands', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->bot(make_bot())->getRegisteredCommands();
    })->toMatchTelegramSnapshot();
});

it('can register commands', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->bot(make_bot())->registerBotCommands([
            'foo' => 'first command',
            'bar' => 'second command',
        ]);
    })->toMatchTelegramSnapshot();
});

it('can register commands with token', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->bot("3f3814e1-5836-3d77-904e-60f64b15df36")->registerBotCommands([
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
