---
title: 'Message Keyboards' 
menuTitle: 'Message Keyboards' 
description: ''
category: 'Features' 
fullscreen: false 
position: 32
---

A keyboard can be added to a message in order to offer a set of options to the user:

<img src="screenshots/keyboard-example.png" />

## Attaching a keyboard

A keyboard can be added to a message using the `->keyboard()` command, passing a new `Keyboard` object as argument.

`Keyboard` has a fluent way to define its buttons and other properties (rows, button chunking, etc.):

buttons can be set up using the `Keyboard::make()->buttons()` method and are defined as a `Button` array

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

Telegraph::message('hello world')
->keyboard(Keyboard::make()->buttons([
        Button::make('Delete')->action('delete')->param('id', '42'),
        Button::make('open')->url('https://test.it'),
]))->send();
```

## Buttons

Each `Button` can be defined using its fluent methods and can be of two types:

### Callback Buttons

Must define an `action` and some `params`. They triggers a **callback query** to be handled by a custom webhook

```php
Button::make('Delete')->action('delete')->param('id', '42'),
```

### URL Buttons

Must define an `url` and are used to open an external url when pressed:

```php
Button::make('open')->url('https://test.it'),
```

## Keyboard Rows

A keyboard will normally place one button per row, this behaviour can be customized by defining rows or by chunking buttons

### by rows

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

$keyboard = Keyboard::make()
    ->row([
        Button::make('Delete')->action('delete')->param('id', '42'),
        Button::make('Dismiss')->action('dismiss')->param('id', '42'),
    ])
    ->row([
        Button::make('open')->url('https://test.it'),
    ]);
```

### by chunking

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

$keyboard = Keyboard::make()
    ->buttons([
        Button::make('Delete')->action('delete')->param('id', '42'),
        Button::make('Dismiss')->action('dismiss')->param('id', '42'),
        Button::make('open')->url('https://test.it'),
    ])->chunk(2);
```

## Updating a keyboard

A keyboard can be replaced by a new one by submitting its `messageId`:

```php
Telegraph::replaceKeyboard(
    messageId: 1568794, 
    newKeyboard: Keyboard::make()->buttons([
        Button::make('Delete')->action('delete')->param('id', '42'),
        Button::make('open')->url('https://test.it'),
    ])
)->send();
```

## Deleting a keyboard

A keyboard can be removed by submitting its `messageId`:

```php
Telegraph::deleteKeyboard(messageId: 1568794)->send();
```
