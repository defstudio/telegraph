<?php /** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('can return the telegram request url', function () {
    $url = app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->getUrl();

    expect($url)->toMatchSnapshot();
});

it('can customize the destination bot', function () {
    $bot = make_bot();
    $telegraph = app(Telegraph::class)
        ->bot($bot)
        ->registerWebhook();

    expect($telegraph->getUrl())->toStartWith("https://api.telegram.org/bot$bot->token/");
});

it('can customize the destination chat', function () {
    $url = app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->getUrl();

    expect($url)->toMatchSnapshot();
});

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

it('can add a keyboard to a message', function () {
    Http::fake();

    app(Telegraph::class)
        ->chat(make_chat())
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
        ->chat(make_chat())
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
