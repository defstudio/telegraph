---
title: 'Adding a chat to a Telegraph Bot'
menuTitle: 'Adding a chat'
description: ''
category: 'Quickstart'
fullscreen: false 
position: 23
---


Associating a chat to a bot, lets you send messages to that chat and interacting with commands

**note** to get the _chat_id_ write the `/chat_id` command inside the chat

### through an artisan command

```shell
php artisan telegraph:new-chat {bot_id}
```

the bot_id argument is mandatory if you have created more than one bot

## programmatically

If you are implementing a custom bot creation logic, you can create a new chat using the `TelegraphChat` model:

```php
/** @var TelegraphChat $chat */
$chat = $telegraph_bot->chats()->create([
    'chat_id' => $chat_id,
    'name' => $chat_name,
]);
```
