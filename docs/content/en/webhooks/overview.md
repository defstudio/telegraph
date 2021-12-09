---
title: 'Webhooks Overview'
menuTitle: 'Overview'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 40
---

Telegram bots can interact with chats and users through a webhook system that enables it to be updated about chats changes, new commands and user interactions without continuously polling Telegram APIs for updates.

## Default Handler

A default "do nothing" handler is shipped with Telegraph installation, it can only handle a single chat command:

```
/chatid
```

And answers with the ID of the chat the command is issued into. It is useful to get the ChatID in order to register a new chat in Telegraph


## Custom Handler

In order to write custom webhook and commands handlers the default handler must be switched with a custom one

```php
// app/Http/Webhooks/MyWebhookHandler.php

class MyWebhookHandler extends \DefStudio\Telegraph\Handlers\WebhookHandler
{
    public function myCustomHandler(): void
    {
        // ... My awesome code
    }
}
```

<alert type="alert">**Note:** A custom webhook handler must extend `DefStudio\Telegraph\Handlers\WebhookHandler`.</alert>

A detailed description of how WebhookHandlers work can be found in the next sections

## Webhook request types

Telegraph can handle two incoming webhook request types: **Chat Messages** and **Callback Queries**

### Chat Messages

Telegraph bots can receive commands from chats where they are member of. A command starts with

[TODO after having checked if telegraph can handle entire messages alongside commands]
