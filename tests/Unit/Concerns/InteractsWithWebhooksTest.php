<?php

use DefStudio\Telegraph\Telegraph;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('can register a webhook', function () {
    Http::fake();

    app(Telegraph::class)
        ->bot(make_bot())
        ->registerWebhook()
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});

it('can get webhook debug info', function () {
    Http::fake();

    app(Telegraph::class)
        ->bot(make_bot())
        ->getWebhookDebugInfo()
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});

it('can reply to a webhook call', function () {
    Http::fake();

    app(Telegraph::class)
        ->bot(make_bot())
        ->replyWebhook(2123456, 'foo')
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});
