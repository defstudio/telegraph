<?php

use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Telegraph;

test('keyboard resize', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph
            ->chat(make_chat())
            ->html('foobar')
            ->replyKeyboard(
                ReplyKeyboard::make()
                    ->row([ReplyButton::make('foo')->requestContact()])
                    ->resize()
            );
    })->toMatchTelegramSnapshot();
});

test('keyboard resize with ', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph
            ->chat(make_chat())
            ->html('foobar')
            ->replyKeyboard(
                ReplyKeyboard::make()
                    ->row([
                        ReplyButton::make('foo 1')->requestContact(),
                        ReplyButton::make('foo 2')->requestContact(),
                        ReplyButton::make('foo 3')->requestContact(),
                    ])
                    ->resize()
                    ->chunk(2)
            );
    })->toMatchTelegramSnapshot();
});
