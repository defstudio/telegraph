---
title: 'Manual updates polling'
menuTitle: 'Manual Polling'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 66
---

As an aternative to set up a webhook, a bot updates can be fetched by periodically polling Telegram APIs

<alert type="alert">Manual updates polling is not available if a webhook is set up for the bot. Webhook should be remove first using its [deleteWebhook](webhooks/deleting-webhooks) methods</alert>

in order to get the updates, an `->updates()` method is available in the [TelegraphBot](models/telegraph-bot) model:

```php
$updates = $telegraphBot->updates();
```

the call will result in a collection of [`DefStudio\Telegraph\DTO\TelegramUpdate`](webhooks/dto#telegram-update) instances, one for each update, sorted by the oldest one. 

It is advised to keep track of the  `TelegramUpdate::id()` in order to avoid processing the same update multiple times.

The content of each update depends on the update type ([Chat Message](webhooks/webhook-request-types#chat-messages) or [Callback Query](webhooks/webhook-request-types#callback-queries)) and on the specific message type. Refer to the [DTO](wehbhooks/dto) section for more details.
