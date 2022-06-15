<?php

use DefStudio\Telegraph\Client\TelegraphResponse;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Http;

test('sync sending returns a Telegraph Response', function () {
    Http::fake();

    $response = app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->send();

    expect($response)->toBeInstanceOf(TelegraphResponse::class);
});

test('async sending returns a Pending Dispatch', function () {
    Http::fake();

    $response = app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->dispatch();

    expect($response)->toBeInstanceOf(PendingDispatch::class);
});
