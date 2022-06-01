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

## Inline queries

Users can interact with a bot through inline queries calling it by typing its name followed by the query. The query is sent to the webhook (or can be [manually polled](webhooks/manual-polling)) wrapped in a [`DefStudio\Telegraph\DTO\InlineQuery`](webhooks/dto#defstudio-telegraph-dto-inline-query). For more information see [inline bot page](https://core.telegram.org/bots/inline) and [the official api documentation](https://core.telegram.org/bots/api#inline-mode)

<alert type="alert">Inline queries should be enabled inside bot configuration (see [here](https://core.telegram.org/bots/inline) for more info)</alert>

Inside a `WebhookHandler`, incoming inline queries are handled by overriding the `handleInlineQuery` method:

```php
use DefStudio\Telegraph\DTO\InlineQuery;use DefStudio\Telegraph\DTO\InlineQueryResultPhoto;

class CustomWebhookHandler extends WebhookHandler
{
    public function handleInlineQuery(InlineQuery $inlineQuery): void
    {
        $query = $inlineQuery->query(); // "pest logo"
        
        $logo = LogoFinder::search($query); // the code to handle the query. just an example here
        
        $this->bot->answerInlineQuery($inlineQuery->id(), [
            InlineQueryResultPhoto::make($logo->id."-light", "https://logofinder.dev/$logo->id/light.jpg", "https://logofinder.dev/$logo->id/light/thumb.jpg")
                ->caption('Light Logo'),
            InlineQueryResultPhoto::make($logo->id."-dark", "https://logofinder.dev/$logo->id/dark.jpg", "https://logofinder.dev/$logo->id/dark/thumb.jpg")
                ->caption('Light Logo'),
        ])->send();
    }
}
```

Different kind of result can be sent through the handler: 

- Article (coming soon)
- Audio (coming soon)
- Contact (coming soon)
- Game (coming soon)
- Document (coming soon)
- Gif ([`DefStudio\Telegraph\DTO\InlineQueryResultGif`](webhooks/dto#defstudio-telegraph-dto-inline-query-result-gif))
- Location (coming soon)
- Mpeg4Gif (coming soon)
- Photo([`DefStudio\Telegraph\DTO\InlineQueryResultPhoto`](webhooks/dto#defstudio-telegraph-dto-inline-query-result-photo))
- Venue (coming soon)
- Video (coming soon)
- Voice (coming soon)
