<?php

use DefStudio\Telegraph\Telegraph;

it('can send an html message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar');
    })->toMatchTelegramSnapshot();
});

it('can send a markdown message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->markdown('foobar');
    })->toMatchTelegramSnapshot();
});

it('can send protected content', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->markdown('test')->protected();
    })->toMatchTelegramSnapshot();
});

it('can send silent messages', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->markdown('test')->silent();
    })->toMatchTelegramSnapshot();
});

it('can disable url preview', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->markdown('test')->withoutPreview();
    })->toMatchTelegramSnapshot();
});

it('can reply to a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->markdown('test')->reply(123456);
    })->toMatchTelegramSnapshot();
});

it('can delete a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->deleteMessage(123456);
    })->toMatchTelegramSnapshot();
});

it('can edit a message', function (callable $setupClosure) {
    expect($setupClosure)->toMatchTelegramSnapshot();
})->with([
    'edit before text' => fn () => function (Telegraph $telegraph) {
        return $telegraph->edit(123456)->markdown('new text');
    },
    'edit after text' => fn () => function (Telegraph $telegraph) {
        return $telegraph->markdown('new text')->edit(123456);
    },
]);

it('can send a location a message', function () {
    expect(function (Telegraph $telegraph) {
        $telegraph->location(12.345,-54.321);
    })->toMatchTelegramSnapshot();
});
