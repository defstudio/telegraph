<?php

use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('can add a keyboard to a message', function () {
    Http::fake();

    app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->keyboard(Keyboard::make()->button('foo')->url('bar'))
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});

it('can add a keyboard as an array', function () {
    Http::fake();

    app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->keyboard([
            [
                ['text' => 'foo', 'url' => 'bar'],
            ],
        ])
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});

it('can add a keyboard as a closure', function () {
    Http::fake();

    app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->keyboard(fn ($keyboard) => $keyboard->button('foo')->url('bar'))
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
        ->replaceKeyboard('123456', Keyboard::make()->buttons([
            Button::make('foo')->url('bar'),
        ]))
        ->send();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toMatchSnapshot();

        return true;
    });
});
