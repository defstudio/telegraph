<?php

use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

test('keyboard fluent creation', function () {
    $keyboard = Keyboard::make()
        ->row([
            Button::make('foo')
                ->action('bar')
                ->param('key1', 'baz')
                ->param('key2', 'quuz'),
            Button::make('visit')
                ->url('https://acme.com'),
        ])
        ->row([
            Button::make('visit 2')
                ->url('https://google.com'),
        ])->toArray();

    expect($keyboard)->toMatchArray([
        [
            ['text' => 'foo', 'callback_data' => 'action:bar;key1:baz;key2:quuz'],
            ['text' => 'visit', 'url' => 'https://acme.com'],
        ],
        [
            ['text' => 'visit 2', 'url' => 'https://google.com'],
        ],
    ]);
});

test('keyboard fast fluent creation', function () {
    $keyboard = Keyboard::make()
        ->buttons([
            Button::make('foo')
                ->action(' bar')
                ->param('key1', 'baz ')
                ->param('key2', ' quuz '),
            Button::make('visit')
                ->url('https://acme.com'),
            Button::make('visit 2')
                ->url('https://google.com'),
        ])->chunk(2)->toArray();

    expect($keyboard)->toMatchArray([
        [
            ['text' => 'foo', 'callback_data' => 'action:bar;key1:baz;key2:quuz'],
            ['text' => 'visit', 'url' => 'https://acme.com'],
        ],
        [
            ['text' => 'visit 2', 'url' => 'https://google.com'],
        ],
    ]);
});

test('keboard from array', function () {
    $arrayKeyboard = [
        [
            ['text' => 'foo', 'callback_data' => 'action:bar;key1:baz;key2:quuz'],
            ['text' => 'visit', 'url' => 'https://acme.com'],
        ],
        [
            ['text' => 'visit 2', 'url' => 'https://google.com'],
        ],
    ];

    $keyboard = Keyboard::fromArray($arrayKeyboard);

    expect($keyboard->toArray())->toMatchArray($arrayKeyboard);
});

it('can replace a button', function () {
    $keyboard = Keyboard::make()
        ->row([
            Button::make('quzz')->url('hi'),
        ])
        ->row([
            Button::make('foo')->url('bar'),
            Button::make('baz')->action('quuz'),
        ]);

    $keyboard = $keyboard
        ->replaceButton('foo', Button::make('new1')->url('test'))
        ->replaceButton('quzz', Button::make('new2')->url('test2'));

    expect($keyboard->toArray())->toMatchArray([
        [
            ['text' => 'new2', 'url' => 'test2'],
        ],
        [
            ['text' => 'new1', 'url' => 'test'],
            ['text' => 'baz', 'callback_data' => 'action:quuz'],
        ],
    ]);
});

it('can delete a button', function () {
    $keyboard = Keyboard::make()
        ->row([
            Button::make('quzz')->url('hi'),
            Button::make('foo')->url('bar'),
            Button::make('baz')->action('quuz'),
            Button::make('foo')->url('bar'),
        ])
        ->buttons([
            Button::make('foo')->url('bar'),
            Button::make('baz')->action('quuz'),
        ]);

    $keyboard = $keyboard->deleteButton('foo');

    expect($keyboard->toArray())->toMatchArray([
        [
            ['text' => 'quzz', 'url' => 'hi'],
            ['text' => 'baz', 'callback_data' => 'action:quuz'],
        ],
        [
            ['text' => 'baz', 'callback_data' => 'action:quuz'],
        ],
    ]);
});

it('can flatten its buttons', function () {
    $keyboard = Keyboard::make()
        ->row([
            Button::make('quzz')->url('hi'),
            Button::make('foo')->url('bar'),
            Button::make('baz')->action('quuz'),
            Button::make('foo')->url('bar'),
        ])
        ->buttons([
            Button::make('foo')->url('bar'),
            Button::make('baz')->action('quuz'),
        ]);

    $keyboard = $keyboard->flatten();

    expect($keyboard->toArray())->toMatchArray([
        [['text' => 'quzz', 'url' => 'hi']],
        [['text' => 'foo', 'url' => 'bar']],
        [['text' => 'baz', 'callback_data' => 'action:quuz']],
        [['text' => 'foo', 'url' => 'bar']],
        [['text' => 'foo', 'url' => 'bar']],
        [['text' => 'baz', 'callback_data' => 'action:quuz']],
    ]);
});
