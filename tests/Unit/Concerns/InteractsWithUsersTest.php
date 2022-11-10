<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */

it('can retrieve user profile photos', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->userProfilePhotos(123456);
    })->toMatchTelegramSnapshot();
});
