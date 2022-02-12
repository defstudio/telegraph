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

keyboard buttons must be organized in rows:

```php
Telegraph::message('hello world')
->keyboard([
    [ // ROW 1
        [ // BUTTON 1
            "text" => "🗑️ Delete",
            "callback_data" => "action:delete;id:$notification->id"
        ], 
        [ // BUTTON 2
            "text" => "📖 Mark as Read", 
            "callback_data" => "action:read;id:$notification->id"
        ],
    ],
    [ // ROW 2
        [ // BUTTON 3
            "text" => "👀 Open", 
            "url" => 'http://test.it'
        ],
    ],
])
->send();
```

and can be of two types

### Callback Buttons

must contain a `callback_data` field and triggers a **callback query** to be handled by a custom webhook

```php
[ 
    "text" => "🗑️ Delete",
    "callback_data" => "action:delete;id:$notification->id"
], 
```

### URL Buttons

must contain an `url` field and are used to open an external url when pressed:

```php
[
    "text" => "👀 Open",
    "url" => 'http://test.it'
],
```


## Fluent keyboard definition

A keyboard can also be built in a fluent way:

### by rows

```php
$keyboard = Keyboard::make()
    ->row([
        Button::make('Delete')
            ->action('delete')
            ->param('id', '42'),
        Button::make('Dismiss')
            ->action('dismiss')
            ->param('id', '42'),
    ])
    ->row([
        Button::make('open')
            ->url('https://test.it'),
    ]);
```

### by buttons

```php
$keyboard = Keyboard::make()
    ->buttons([
        Button::make('Delete')
            ->action('delete')
            ->param('id', '42'),
        Button::make('Dismiss')
            ->action('dismiss')
            ->param('id', '42'),
        Button::make('open')
            ->url('https://test.it'),
    ])->chunk(2);
```


## Updating a keyboard

A keyboard can be replaced by a new one by submitting its `messageId`:

```php
Telegraph::replaceKeyboard(messageId: 1568794, newKeyboard: [
    [
        [
            "text" => "🗑️ Delete",
            "callback_data" => "action:delete;id:$notification->id"
        ], 
    ],
    [
        [
            "text" => "👀 Open", 
            "url" => 'http://test.it'
        ],
    ],
])
->send();
```

## Deleting a keyboard

A keyboard can be removed by submitting its `messageId`:

```php
Telegraph::deleteKeyboard(messageId: 1568794)->send();
```
