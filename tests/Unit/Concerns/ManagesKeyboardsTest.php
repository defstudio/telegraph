<?php

use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Telegraph;

it('can add a keyboard to a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->html('foobar')
            ->keyboard(Keyboard::make()->button('foo')->url('bar'));
    })->toMatchTelegramSnapshot();
});

it('can add a keyboard as an array', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph ->chat(make_chat())
            ->html('foobar')
            ->keyboard([
                [
                    ['text' => 'foo', 'url' => 'bar'],
                ],
            ]);
    })->toMatchTelegramSnapshot();
});

it('can add a keyboard as a closure', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->html('foobar')
            ->keyboard(fn ($keyboard) => $keyboard->button('foo')->url('bar'));
    })->toMatchTelegramSnapshot();
});

it('can replace the keyboard of a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->replaceKeyboard('123456', Keyboard::make()->buttons([
                Button::make('foo')->url('bar'),
            ]));
    })->toMatchTelegramSnapshot();
});
