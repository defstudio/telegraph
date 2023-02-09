<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */

use DefStudio\Telegraph\Telegraph;

it('can retrieve bot commands', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->bot(make_bot())->getMyCommands();
    })->toMatchTelegramSnapshot();
});
