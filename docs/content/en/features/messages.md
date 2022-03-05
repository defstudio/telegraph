---
title: 'Messages'
menuTitle: 'Messages'
description: ''
category: 'Features'
fullscreen: false 
position: 30
---

Messages can be sent to a Telegram chat using a `TelegraphChat` model

```php
use DefStudio\Telegraph\Models\TelegraphChat;

$chat = TelegraphChat::find(44);

// this will use the default parsing method set in config/telegraph.php
$chat->message('hello')->send();

$chat->html("<b>hello<b>\n\nI'm a bot!")->send();

$chat->markdown('*hello*')->send();
```

## Additional methods

Telegraph allows to send complex messages using its methods:

### protectContent

Protects the contents of the sent message from forwarding and saving

```php
$chat->message("please don't share this")->protectContent()->send();
```
