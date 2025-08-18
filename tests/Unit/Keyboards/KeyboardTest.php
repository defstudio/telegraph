<?php

use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

test('keyboard creation by rows', function () {
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

test('keyboard creation by buttons', function () {
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

it('can quickly add buttons', function () {
    $keyboard = Keyboard::make()
        ->button('Delete')->action('delete')->param('id', '42')
        ->button('open')->url('https://test.it')
        ->button('foo')->webApp('https://my-webapp.dev')
        ->button('switch')->switchInlineQuery('test')
        ->button('switch here')->switchInlineQuery('test 2')->currentChat()
        ->button('foo')->loginUrl('https://my-loginUrl.dev')
        ->button('Copy text')->copyText('text123')
        ->chunk(2);

    expect($keyboard->toArray())->toBe([
        [
            ['text' => 'Delete', 'callback_data' => 'action:delete;id:42'],
            ['text' => 'open', 'url' => 'https://test.it'],
        ],
        [
            ['text' => 'foo', 'web_app' => ['url' => 'https://my-webapp.dev']],
            ['text' => 'switch', 'switch_inline_query' => 'test'],
        ],
        [
            ['text' => 'switch here', 'switch_inline_query_current_chat' => 'test 2'],
            ['text' => 'foo', 'login_url' => ['url' => 'https://my-loginUrl.dev']],
        ],
        [
            ['text' => 'Copy text', 'copy_text' => ['text' => 'text123']],
        ],
    ]);
});

it('can handle conditional closures', function () {
    $keyboard = Keyboard::make()
        ->button('Delete')->action('delete')->param('id', '42')
        ->when(true, fn (Keyboard $keyboard) => $keyboard->button('Test')->action('test')->param('foo', 66))
        ->when(false, fn (Keyboard $keyboard) => $keyboard->button('Unwanted Test')->action('unwanted_test')->param('foo', 33));

    expect($keyboard->toArray())->toBe([
        [
            ['text' => 'Delete', 'callback_data' => 'action:delete;id:42'],
        ],
        [
            ['text' => 'Test', 'callback_data' => 'action:test;foo:66'],
        ],
    ]);
});

it('can right to left layout for buttons', function () {
    $keyboard = Keyboard::make()
        ->row([
            Button::make('foo')->url('bar'),
            Button::make('baz')->action('quuz'),
        ])
        ->row([
            Button::make('baz')->action('quuz'),
            Button::make('foo')->url('bar'),
        ]);

    $keyboard->rightToLeft();

    expect($keyboard->toArray())->toMatchArray([
        [
            ['text' => 'baz', 'callback_data' => 'action:quuz'],
            ['text' => 'foo', 'url' => 'bar'],
        ],
        [
            ['text' => 'foo', 'url' => 'bar'],
            ['text' => 'baz', 'callback_data' => 'action:quuz'],
        ],
    ]);
});

it('can create copy text buttons', function () {
    $button = Button::make('Copy text123')->copyText('text123');

    expect($button->toArray())->toBe([
        'text' => 'Copy text123',
        'copy_text' => [
            'text' => 'text123',
        ],
    ]);
});

it('can create keyboard with copy text buttons', function () {
    $keyboard = Keyboard::make()
        ->row([
            Button::make('Copy text')->copyText('Hello World!'),
            Button::make('Delete')->action('delete')->param('id', '42'),
        ]);

    expect($keyboard->toArray())->toMatchArray([
        [
            ['text' => 'Copy text', 'copy_text' => ['text' => 'Hello World!']],
            ['text' => 'Delete', 'callback_data' => 'action:delete;id:42'],
        ],
    ]);
});

it('can parse keyboard from array with copy text buttons', function () {
    $arrayKeyboard = [
        [
            ['text' => 'Copy text', 'copy_text' => ['text' => 'Hello World!']],
            ['text' => 'Delete', 'callback_data' => 'action:delete;id:42'],
        ],
        [
            ['text' => 'Copy URL', 'copy_text' => ['text' => 'https://example.com/']],
        ],
    ];

    $keyboard = Keyboard::fromArray($arrayKeyboard);

    expect($keyboard->toArray())->toMatchArray($arrayKeyboard);
});

it('can use fluent keyboard builder with copy text buttons', function () {
    $keyboard = Keyboard::make()
        ->button('Copy Username')->copyText('@johndoe')
        ->button('Copy Email')->copyText('john@example.com')
        ->button('Delete User')->action('delete')->param('id', 'user123');

    expect($keyboard->toArray())->toBe([
        [
            ['text' => 'Copy Username', 'copy_text' => ['text' => '@johndoe']],
        ],
        [
            ['text' => 'Copy Email', 'copy_text' => ['text' => 'john@example.com']],
        ],
        [
            ['text' => 'Delete User', 'callback_data' => 'action:delete;id:user123'],
        ],
    ]);
});
