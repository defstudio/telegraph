---
title: 'Register Webhooks'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 61
---

In order to receive Telegram updates through a webhook, it has to be registered to a specific bot. This can be accomplished both programmatically and through an artisan command

## artisan command

You can register a webhook calling the `telegraph:set-webhook` artisan command:

```shell
php artisan telegraph:set-webhook
```

## programmatically

if you are implementing a custom bot management logic, you can register a webhok using the `TelegraphBot` model:

```php
/** @var DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->registerWebhook()->send();
```

<alert type="alert">Manual updates polling is not available if a webhook is set up for the bot. Webhook should be remove first using its [unregisterWebhook](webhooks/deleting-webhooks) method</alert>
