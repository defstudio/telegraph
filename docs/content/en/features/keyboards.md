---
title: 'Message Keyboards'
menuTitle: 'Message Keyboards'
description: ''
category: 'Features'
fullscreen: false 
position: 32
---

A keyboard can be added to a message in order to offer a set of options to the user:

<img src="screenshots/first-message.png" />


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
