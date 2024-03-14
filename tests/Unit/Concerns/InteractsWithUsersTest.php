<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */

use DefStudio\Telegraph\Telegraph;

it('can retrieve user profile photos', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->userProfilePhotos(123456);
    })->toMatchTelegramSnapshot();
});
