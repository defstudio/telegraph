---
title: 'Sending a message'
menuTitle: 'Sending a message'
description: ''
category: 'Quickstart'
fullscreen: false 
position: 24
---


After a bot and at least one chat has been set up, this package can be used to post new messages:

```php
$chat = TelegraphChat::first();

$chat->html("<strong>Hello!<strong>\n\nI'm here!")->send();
```

<img src="screenshots/first-message.png" />

as an alternative, messages can be formatted with markdown:

```php
$chat->markdown("*Hello!*\n\nI'm here!")->send();
```
