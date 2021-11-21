<?php

use DefStudio\Telegraph\Telegraph;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('can return the telegram request url', function () {
    $url = app(Telegraph::class)
        ->html('foobar')
        ->getUrl();

    expect($url)->toMatchSnapshot();
});

it('can customize the destination bot', function () {
    $telegraph = app(Telegraph::class)
        ->bot('foo')
        ->registerWebhook();

    expect($telegraph->getUrl())->toStartWith('https://api.telegram.org/botfoo/');
});

it('can customize the destination chat', function () {
    $url = app(Telegraph::class)
        ->html('foobar')
        ->chat('123456')
        ->getUrl();

    expect($url)->toMatchSnapshot();
});

it('can send an html message', function () {
    Http::fake();

    app(Telegraph::class)
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
        ->markdown('foobar')
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});

it('can add a keyboard to a message', function () {
    Http::fake();

    app(Telegraph::class)
        ->html('foobar')
        ->keyboard([
            ['foo' => 'bar'],
        ])
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});

it('can replace the keyboard of a message', function () {
    Http::fake();

    app(Telegraph::class)
        ->replaceKeyboard('123456', [
            ['foo' => 'bar'],
        ])
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});

it('can register a webhook', function () {
    Http::fake();

    app(Telegraph::class)
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
        ->replyWebhook(2123456, 'foo')
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});
