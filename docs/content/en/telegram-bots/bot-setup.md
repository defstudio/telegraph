---
title: Bot Setup 
description: ''
category: 'Telegram Bots'
fullscreen: true 
position: 20
---

## Creating a new Telegram Bot

1. Go to [@BotFather](https://t.me/botfather) on Telegram.

2. Send `/newbot`, to start creating a new Bot and setting its name and username.

<img src="screenshots/new-bot.jpg" />

3. take note of the bot **token**.

<img src="screenshots/new-bot-token.jpg" />

4. Now you need to allow your Bot to send direct messages, so send `/setjoingroups` to @BotFather, select your Bot and click Enable:

<img src="screenshots/new-bot-joingroups.jpg" />

5. (optional) To let your bot to listen for commands (like `/chatid`) you need to enable privacy mode: send `/ 

<img src="screenshots/new-bot-setprivacy.jpg" />

## Registering the newly created bot into your application

Any number of bots can be created, both programmatically and through an artisan command

### through artisan command

You can add a new bot issuing the dedicated _artisan_ command:

```shell
php artisan telegraph:new-bot
```
you will be guided through a bot creation wizard that will (optionally) allow you to add a new chat and setup a bot webhook as well

<img src="screenshots/artisan-new-bot.jpg" />

### programmatically

If you are implementing a custom bot creation logic, you can create a new bot using the `TelegramBot` model:

```php
$bot = TelegraphBot::create([
    'token' => $token,
    'name' => $name,
]);
```

## Setting a webhook

A webhook let your bot to answer commands issued from telegram chats and buttons inside messages

### through an artisan command

```shell
php artisan telegraph:set-webhook {bot_id}
```

the bot_id argument is mandatory if you have created more than one bot

### programmatically

```php
/** @var TelegraphBot $bot */
$bot->registerWebhook()->send();
```

## Associating a chat to a bot

Associating a chat to a bot, lets you send messages to that chat and interacting with commands

**note** to get the _chat_id_ write the `/chat_id` command inside the chat

### through an artisan command

```shell
php artisan telegraph:new-chat {bot_id}
```

the bot_id argument is mandatory if you have created more than one bot

## programmatically

[TODO]
