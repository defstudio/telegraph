---
title: 'Webhook Request Types'
menuTitle: 'Request Types'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 64
---


Telegraph can handle two incoming webhook request types: **Chat Messages** and **Callback Queries**

## Chat Messages

Telegraph bots can receive commands from chats where they are registered. A command is a telegram message has a a `backslash` char followed by a descriptive word, typed in the bot's chat:

```
\hi
```

what the command will trigger is up to the developer, but a webhook will react to it if it has a public method named as the command:

```php
class CustomWebhookHandler extends WebhookHandler
{
    public function hi()
    {
        $this->chat->markdown("*Hi* happy to be here!")->send();
    }
}
```

The full chat message data can be retrieved through the [`DefStudio\Telegraph\DTO\Message`](webhooks/dto#defstudio-telegraph-dto-message) DTO:

```php
class CustomWebhookHandler extends WebhookHandler
{
    public function hi()
    {
        $text = $this->message()->text(); //hi
    }
}
```

<alert type="alert">As it is used internally, `/handle` command keyword is forbidden</alert>

## Callback Queries

Bots messages may ship with keyboard of buttons that trigger actions when pressed:

<img src="screenshots/keyboard-example.png" />

when pressed, a new call will be forwarded to the webhook with the following payload

```
action:dismiss;notification-id:42
```

and the `dismiss` action will be handled by a corresponding public method in the custom webhook handler:

```php
class CustomWebhookHandler extends WebhookHandler
{
    public function dismiss()
    {
        $notificationId = $this->data->get('notification-id'); //42
        
        Notification::find($notificationId)->dismiss();
        
        $this->reply("notification dismissed");
    }
}
```

The full callback query data can be retrieved through the [`DefStudio\Telegraph\DTO\CallbackQuery`](webhooks/dto#defstudio-telegraph-dto-callback-query) DTO

```php
class CustomWebhookHandler extends WebhookHandler
{
    public function dismiss()
    {
        $notificationId = $this->callbackQuery->data()->get('notification-id'); //42
        
        Notification::find($notificationId)->dismiss();
        
        $this->reply("notification dismissed");
    }
}
```
