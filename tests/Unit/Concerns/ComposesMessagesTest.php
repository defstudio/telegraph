<?php

use DefStudio\Telegraph\Telegraph;

it('can send an html message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->html('foobar'))
        ->toMatchTelegramSnapshot();
});

it('can send a markdown message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdown('foobar'))
        ->toMatchTelegramSnapshot();
});

it('can send a markdownV2 message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdownV2('foobar'))
        ->toMatchTelegramSnapshot();
});

it('can send protected content', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdown('test')->protected())
        ->toMatchTelegramSnapshot();
});

it('can send silent messages', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdown('test')->silent())
        ->toMatchTelegramSnapshot();
});

it('can disable url preview', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdown('test')->withoutPreview())
        ->toMatchTelegramSnapshot();
});

it('can reply to a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdown('test')->reply(123456))
        ->toMatchTelegramSnapshot();
});

it('can delete a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->deleteMessage(123456))
        ->toMatchTelegramSnapshot();
});

it('can delete messages', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->deleteMessages([123456, 654321, 11111]))
        ->toMatchTelegramSnapshot();
});

it('can pin a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->pinMessage(123456))
        ->toMatchTelegramSnapshot();
});

it('can unpin a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->unpinMessage(123456))
        ->toMatchTelegramSnapshot();
});

it('can unpin al messages', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->unpinAllMessages())
        ->toMatchTelegramSnapshot();
});

it('can edit a message', function (callable $setupClosure) {
    expect($setupClosure)->toMatchTelegramSnapshot();
})->with([
    'edit before text' => fn (Telegraph $telegraph) => $telegraph->edit(123456)->markdown('new text'),
    'edit after text' => fn (Telegraph $telegraph) => $telegraph->markdown('new text')->edit(123456),
]);

it('can forward a message', function () {
    $chat = make_chat();

    expect(fn (Telegraph $telegraph) => $telegraph->forwardMessage($chat, 123456))
        ->toMatchTelegramSnapshot();
});
