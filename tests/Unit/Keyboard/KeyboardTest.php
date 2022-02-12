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
                ->action('bar')
                ->param('key1', 'baz')
                ->param('key2', 'quuz'),
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
