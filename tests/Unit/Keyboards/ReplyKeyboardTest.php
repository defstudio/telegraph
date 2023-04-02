<?php

use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

test('keyboard creation by rows', function () {
    $keyboard = ReplyKeyboard::make()
        ->row([
            ReplyButton::make('foo'),
            ReplyButton::make('bar')->requestContact(),
        ])
        ->row([
            ReplyButton::make('baz')->requestLocation(),
        ])->toArray();

    expect($keyboard)->toMatchArray([
        [
            ['text' => 'foo'],
            ['text' => 'bar', 'request_contact' => true],
        ],
        [
            ['text' => 'baz', 'request_location' => true],
        ],
    ]);
});

test('keyboard creation by buttons', function () {
    $keyboard = ReplyKeyboard::make()
        ->buttons([
            ReplyButton::make('foo')->requestPoll(),
            ReplyButton::make('bar')->requestQuiz(),
            ReplyButton::make('baz')->webApp('https://webapp.dev'),
        ])->chunk(2)->toArray();

    expect($keyboard)->toMatchArray([
        [
            ['text' => 'foo', 'request_poll' => ['type' => 'regular']],
            ['text' => 'bar', 'request_poll' => ['type' => 'quiz']],
        ],
        [
            ['text' => 'baz', 'web_app' => ['url' => 'https://webapp.dev']],
        ],
    ]);
});

test('keboard from array', function () {
    $arrayKeyboard = [
        [
            ['text' => 'foo', 'request_poll' => ['type' => 'regular']],
            ['text' => 'bar', 'request_poll' => ['type' => 'quiz']],
        ],
        [
            ['text' => 'baz', 'web_app' => ['url' => 'https://webapp.dev']],
        ],
    ];

    $keyboard = ReplyKeyboard::fromArray($arrayKeyboard);

    expect($keyboard->toArray())->toMatchArray($arrayKeyboard);
});

it('can replace a button', function () {
    $keyboard = ReplyKeyboard::make()
        ->row([
            ReplyButton::make('quzz')->requestContact(),
        ])
        ->row([
            ReplyButton::make('foo')->requestLocation(),
            ReplyButton::make('baz')->requestPoll(),
        ]);

    $keyboard = $keyboard
        ->replaceButton('foo', ReplyButton::make('new1')->requestContact())
        ->replaceButton('quzz', ReplyButton::make('new2')->requestLocation());

    expect($keyboard->toArray())->toMatchArray([
        [
            ['text' => 'new2', 'request_location' => true],
        ],
        [
            ['text' => 'new1', 'request_contact' => true],
            ['text' => 'baz', 'request_poll' => ['type' => 'regular']],
        ],
    ]);
});

it('can delete a button', function () {
    $keyboard = ReplyKeyboard::make()
        ->row([
            ReplyButton::make('quzz')->requestLocation(),
            ReplyButton::make('foo')->requestContact(),
            ReplyButton::make('baz')->requestPoll(),
            ReplyButton::make('foo')->requestQuiz(),
        ])
        ->buttons([
            ReplyButton::make('foo')->requestContact(),
            ReplyButton::make('baz')->requestLocation(),
        ]);

    $keyboard = $keyboard->deleteButton('foo');

    expect($keyboard->toArray())->toMatchArray([
        [
            ['text' => 'quzz', 'request_location' => true],
            ['text' => 'baz', 'request_poll' => ['type' => 'regular']],
        ],
        [
            ['text' => 'baz', 'request_location' => true],
        ],
    ]);
});

it('can flatten its buttons', function () {
    $keyboard = ReplyKeyboard::make()
        ->row([
            ReplyButton::make('quzz')->requestLocation(),
            ReplyButton::make('foo')->requestContact(),
            ReplyButton::make('baz')->requestQuiz(),
            ReplyButton::make('foo')->requestPoll(),
        ])
        ->buttons([
            ReplyButton::make('foo')->requestContact(),
            ReplyButton::make('baz')->requestLocation(),
        ]);

    $keyboard = $keyboard->flatten();

    expect($keyboard->toArray())->toMatchArray([
        [['text' => 'quzz', 'request_location' => true]],
        [['text' => 'foo', 'request_contact' => true]],
        [['text' => 'baz', 'request_poll' => ['type' => 'quiz']]],
        [['text' => 'foo', 'request_poll' => ['type' => 'regular']]],
        [['text' => 'foo', 'request_contact' => true]],
        [['text' => 'baz', 'request_location' => true]],
    ]);
});

it('can quickly add buttons', function () {
    $keyboard = ReplyKeyboard::make()
        ->button('foo')->requestContact()
        ->button('bar')->width(0.5)->requestLocation()
        ->button('baz')->width(0.5)->requestContact();

    expect($keyboard->toArray())->toBe([
        [
            ['text' => 'foo', 'request_contact' => true],
        ],
        [
            ['text' => 'bar', 'request_location' => true],
            ['text' => 'baz', 'request_contact' => true],
        ],
    ]);
});

it('can handle conditional closures', function () {
    $keyboard = ReplyKeyboard::make()
        ->button('foo')->requestContact()
        ->when(true, fn (ReplyKeyboard $keyboard) => $keyboard->button('bar')->width(0.5)->requestLocation())
        ->when(false, fn (ReplyKeyboard $keyboard) => $keyboard->button('baz')->width(0.5)->requestContact());

    expect($keyboard->toArray())->toBe([
        [
            ['text' => 'foo', 'request_contact' => true],
        ],
        [
            ['text' => 'bar', 'request_location' => true],
        ],
    ]);
});

it('can right to left layout for buttons', function () {
    $keyboard = ReplyKeyboard::make()
        ->row([
            ReplyButton::make('quzz')->requestLocation(),
            ReplyButton::make('baz')->requestPoll(),
            ReplyButton::make('foo'),
        ])
        ->row([
            ReplyButton::make('foo'),
            ReplyButton::make('baz')->requestLocation(),
        ]);

    $keyboard->rightToLeft();

    expect($keyboard->toArray())->toMatchArray([
        [
            ['text' => 'foo'],
            ['text' => 'baz', 'request_poll' => ['type' => 'regular']],
            ['text' => 'quzz', 'request_location' => true],
        ],
        [
            ['text' => 'baz', 'request_location' => true],
            ['text' => 'foo'],
        ],
    ]);
});
