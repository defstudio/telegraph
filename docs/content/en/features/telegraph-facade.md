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
->keyboard(Keyboard::make()->buttons([
    Button::make("🗑️ Delete")->action("delete")->param('id', $notification->id),  
    Button::make("📖 Mark as Read")->action("read")->param('id', $notification->id),  
    Button::make("👀 Open")->url('https://test.it'),  
])->chunk(2)
->send();
```
