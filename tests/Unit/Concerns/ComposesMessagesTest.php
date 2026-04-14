<?php

use DefStudio\Telegraph\Telegraph;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Keyboard\Keyboard;

it('can send an html message', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->html('foobar'))
        ->toMatchTelegramSnapshot();
});

it('can send a markdown message', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->markdown('foobar'))
        ->toMatchTelegramSnapshot();
});

it('can send a markdownV2 message', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->markdownV2('foobar'))
        ->toMatchTelegramSnapshot();
});

it('can send protected content', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->markdown('test')->protected())
        ->toMatchTelegramSnapshot();
});

it('can send silent messages', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->markdown('test')->silent())
        ->toMatchTelegramSnapshot();
});

it('can disable url preview', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->markdown('test')->withoutPreview())
        ->toMatchTelegramSnapshot();
});

it('can reply to a message', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->markdown('test')->reply(123456))
        ->toMatchTelegramSnapshot();
});

it('can delete a message', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->deleteMessage(123456))
        ->toMatchTelegramSnapshot();
});

it('can delete messages', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->deleteMessages([123456, 654321, 11111]))
        ->toMatchTelegramSnapshot();
});

it('can pin a message', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->pinMessage(123456))
        ->toMatchTelegramSnapshot();
});

it('can unpin a message', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->unpinMessage(123456))
        ->toMatchTelegramSnapshot();
});

it('can unpin al messages', function() {
    expect(fn(Telegraph $telegraph) => $telegraph->unpinAllMessages())
        ->toMatchTelegramSnapshot();
});

it('can edit a message', function(callable $setupClosure) {
    expect($setupClosure)->toMatchTelegramSnapshot();
})->with([
    'edit before text' => fn(Telegraph $telegraph) => $telegraph->edit(123456)->markdown('new text'),
    'edit after text' => fn(Telegraph $telegraph) => $telegraph->markdown('new text')->edit(123456),
]);

it('can forward a message', function() {
    $chat = make_chat();

    expect(fn(Telegraph $telegraph) => $telegraph->forwardMessage($chat, 123456))
        ->toMatchTelegramSnapshot();
});

it('can read business message', function() {
    $chat = make_chat();

    expect(fn(Telegraph $telegraph) => $telegraph->readBusinessMessage(123)->inBusiness(321))
        ->toMatchTelegramSnapshot();
});

it('can delete business messages', function() {
    $chat = make_chat();

    expect(fn(Telegraph $telegraph) => $telegraph->deleteBusinessMessages([123])->inBusiness(321))
        ->toMatchTelegramSnapshot();
});

it('can defer bot and chat assignment for a composed message', function() {
    Facade::fake();

    $chat = make_chat();

    Facade::html('foobar')
        ->keyboard(Keyboard::make())
        ->withoutPreview()
        ->bot($chat->bot)
        ->chat($chat)
        ->send();

    Facade::assertSentData(Telegraph::ENDPOINT_MESSAGE, [
        'chat_id' => $chat->chat_id,
        'text' => 'foobar',
        'parse_mode' => Telegraph::PARSE_HTML,
        'disable_web_page_preview' => true,
    ], false);
});

it('throws missing chat only when sending a deferred message', function() {
    expect(fn() => app(Telegraph::class)->html('foobar'))
        ->not->toThrow(TelegraphException::class);

    expect(fn() => app(Telegraph::class)->html('foobar')->bot('test-token')->send())
        ->toThrow(TelegraphException::class, 'No TelegraphChat defined for this request');
});
