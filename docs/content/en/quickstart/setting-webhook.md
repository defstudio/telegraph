---
title: 'Setting a webhook for the bot'
menuTitle: 'Setting a webhook'
description: ''
category: 'Quickstart'
fullscreen: false 
position: 22
---


A webhook lets your bot to answer commands issued from telegram chats and buttons inside messages

### through an artisan command

```shell
php artisan telegraph:set-webhook {bot_id}
```

the `bot_id` argument is mandatory if you have created more than one bot

### programmatically

```php
/** @var TelegraphBot $bot */
$bot->registerWebhook()->send();
```
