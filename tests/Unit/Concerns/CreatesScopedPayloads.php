<?php

/** @noinspection PhpUnhandledExceptionInspection */


use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\ScopedPayloads\TelegraphPollPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphQuizPayload;

it('can create a poll payload', function () {
    $payload = Telegraph::poll("foo");

    expect($payload)
        ->toBeInstanceOf(TelegraphPollPayload::class);
});

it('can create a quiz payload', function () {
    $payload = Telegraph::quiz("foo");

    expect($payload)
        ->toBeInstanceOf(TelegraphQuizPayload::class);
});
