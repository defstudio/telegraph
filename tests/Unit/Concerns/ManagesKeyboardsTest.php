<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Telegraph;

it('can add a keyboard to a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->keyboard(Keyboard::make()->button('foo')->url('bar'));
    })->toMatchTelegramSnapshot();
});

it('can add a keyboard as an array', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->keyboard([
                [
                    ['text' => 'foo', 'url' => 'bar'],
                ],
            ]);
    })->toMatchTelegramSnapshot();
});

it('can add a keyboard as a closure', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->keyboard(fn ($keyboard) => $keyboard->button('foo')->url('bar'));
    })->toMatchTelegramSnapshot();
});

it('can replace the keyboard of a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->replaceKeyboard('123456', Keyboard::make()->buttons([
            Button::make('foo')->url('bar'),
        ]));
    })->toMatchTelegramSnapshot();
});

it('can attach a reply keyboard to a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->replyKeyboard(ReplyKeyboard::make()->button('foo')->requestContact());
    })->toMatchTelegramSnapshot();
});

it('can attach a reply keyboard as an array', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->replyKeyboard([
                [
                    ['text' => 'foo', 'request_location' => true],
                ],
            ]);
    })->toMatchTelegramSnapshot();
});

it('can request to remove a reply keyboard', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->removeReplyKeyboard();
    })->toMatchTelegramSnapshot();
});

it('can request to remove a reply keyboard selectively', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->removeReplyKeyboard(true);
    })->toMatchTelegramSnapshot();
});

it('can attach a reply keyboard as a closure', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->replyKeyboard(fn (ReplyKeyboard $keyboard) => $keyboard->button('foo')->requestContact());
    })->toMatchTelegramSnapshot();
});

it('can attach a reply keyboard and ask client to resize it', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->replyKeyboard(fn (ReplyKeyboard $keyboard) => $keyboard->button('foo')->requestContact()->resize());
    })->toMatchTelegramSnapshot();
});

it('can attach a one-time reply keyboard', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->replyKeyboard(fn (ReplyKeyboard $keyboard) => $keyboard->button('foo')->requestContact()->oneTime());
    })->toMatchTelegramSnapshot();
});

it('can attach a reply keyboard and a text place holder', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->replyKeyboard(fn (ReplyKeyboard $keyboard) => $keyboard->button('foo')->requestContact()->inputPlaceholder('select...'));
    })->toMatchTelegramSnapshot();
});

it('can attach a selective reply keyboard', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->replyKeyboard(fn (ReplyKeyboard $keyboard) => $keyboard->button('foo')->requestContact()->selective());
    })->toMatchTelegramSnapshot();
});

it('can attach a persistent reply keyboard', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->html('foobar')
            ->replyKeyboard(fn (ReplyKeyboard $keyboard) => $keyboard->button('foo')->requestContact()->persistent());
    })->toMatchTelegramSnapshot();
});

it('can force a reply', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->message('foobar')->forceReply();
    })->toMatchTelegramSnapshot();
});

it('can force a reply with placeholder', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->message('foobar')->forceReply('enter your answer...');
    })->toMatchTelegramSnapshot();
});

it('can force a reply selectively', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->message('foobar')->forceReply(selective: true);
    })->toMatchTelegramSnapshot();
});
