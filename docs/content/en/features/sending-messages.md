---
title: 'Sending Messages'
menuTitle: 'Sending Messages'
description: ''
category: 'Features'
fullscreen: false 
position: 30
---

Messages can be sent to a Telegram chat using a `TelegraphChat` model

```php
use DefStudio\Telegraph\Models\TelegraphChat;

$chat = TelegraphChat::find(44);

$chat->message('hello')->send();

$chat->html("<b>hello<b>\n\nI'm a bot!")->send();

$chat->markdown('*hello*')->send();
```

