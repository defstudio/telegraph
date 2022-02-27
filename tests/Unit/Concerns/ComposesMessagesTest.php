<?php

use DefStudio\Telegraph\Telegraph;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('can send an html message', function () {
    Http::fake();

    app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});

it('can send a markdown message', function () {
    Http::fake();

    app(Telegraph::class)
        ->chat(make_chat())
        ->markdown('foobar')
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});
