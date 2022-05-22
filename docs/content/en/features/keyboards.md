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
        Button::make('Web App')->webApp('https://web-app.test.it'),
]))->send();
```

Additionally, a keyboard can be added to a message using a closure:

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

Telegraph::message('hello world')
->keyboard(function(Keyboard $keyboard){
    return $keyboard
        ->button('Delete')->action('delete')->param('id', '42')
        ->button('open')->url('https://test.it');
        ->button('Web App')->webApp('https://web-app.test.it');
})->send();
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

### Web App Buttons

Must declare the web app `url` and are used to start a telegram bot [Web App](https://core.telegram.org/bots/webapps) :

```php
Button::make('open')->webApp('https://webapp.url.dev'),
```




## Keyboard Rows

A keyboard will normally place one button per row, this behaviour can be customized by defining rows, by setting individual buttons width or by chunking buttons

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

### by setting buttons width

A button relative width can be set using a float number the total width percentage to be taken. Buttons will flow through the rows according to their width

this example would define two buttons on the first row and a large button on the second one:

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

$keyboard = Keyboard::make()
    ->button('Delete')->action('delete')->param('id', '42')->width(0.5)
    ->button('Dismiss')->action('dismiss')->param('id', '42')->width(0.5)
    ->button('open')->url('https://test.it');
    ->button('open')->webApp('https://web-app.url.dev');
```

**notes**

 - A button default width is 1 (that's to say, the entire row width)
 - Each width is defined as a float between 0 and 1 that represents the floating point percentage of the row taken by the button.
 - each button will fill the current row or flow in the subsequent one if there isn't enough space left

### by chunking

Buttons can be authomatically chunked in rows using the `->chunk()` method.

This example would return a first row of two buttons, a second row of two buttons and the last row with the remaining button.

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

$keyboard = Keyboard::make()
    ->button('Delete')->action('delete')->param('id', '42')
    ->button('Dismiss')->action('dismiss')->param('id', '42')
    ->button('Share')->action('share')->param('id', '42')
    ->button('Solve')->action('solve')->param('id', '42')
    ->button('Open')->url('https://test.it')
    ->chunk(2);
```

## Updating a keyboard

A message keyboard can be replaced by a new one by submitting its `messageId`:

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

A keyboard can be removed from a message by submitting its `messageId`:

```php
Telegraph::deleteKeyboard(messageId: 1568794)->send();
```
