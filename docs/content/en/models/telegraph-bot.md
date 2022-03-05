<---
title: 'TelegraphBot'
description: ''
category: 'Models'
fullscreen: false 
position: 40
---

Bot informations are stored in database inside a `telegraph_bots` table and can be retrieved using `DefStudio\Telegraph\Models\TelegaphBot` model. It has some useful methods:


## info

retrieves the bot data from telegram

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->info();

/*
 * id: 42
 * is_bot: true
 * first_name: telegraph-test
 * username: test_bot
 * can_join_groups: true
 * can_read_all_group_messages: false
 * supports_inline_queries: false
 */

```

## url()

retrieves the bot url

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->url();

// https://t.me/my-bot-name
```

## registerWebhook

register a webhook url

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->registerWebhook()->send();
```

## getWebhookDebugInfo

retrieves webhook debug data

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->getWebhookDebugInfo()->send();
```

## replyWebhook

replies to a webhook callback

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->replyWebhook($callbackQueryId, 'message received')->send();
```




<alert type="alert">**Note:** follow [installation](installation#set-up) instructions for creating the database tables</alert>

