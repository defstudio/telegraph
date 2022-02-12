---
title: 'Telegraph Facade'
menuTitle: 'Telegraph Facade'
description: ''
category: 'Features'
fullscreen: false 
position: 31
---


In applications that have a single bot writing on a single chat, both will be automatically inferred:

```php
Telegraph::message('hello world')->send();
```

this will allow a fluent tool for interacting with Telegram:

```php
Telegraph::message('hello world')
->keyboard([
    [
        ["text" => "🗑️ Delete", "callback_data" => "action:delete;id:$notification->id"],
        ["text" => "📖 Mark as Read", "callback_data" => "action:read;id:$notification->id"],
    ],
    [
        ["text" => "👀 Open", "url" => 'http://test.it'],
    ],
])
->send();
```
