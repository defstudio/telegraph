---
title: 'TelegraphUpdate'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 67
---

## `DefStudio\Telegraph\DTO\TelegramUpdate`

- `->id()` incoming _update_id_
- `->message()` (optional) an instance of [`DefStudio\Telegraph\DTO\Message`](webhooks/dto#defstudio-telegraph-dto-message) 
- `->callbackQuery()` (optional) an instance of [`DefStudio\Telegraph\DTO\CallbackQuery`](webhooks/dto#defstudio-telegraph-dto-callback-query)


## `DefStudio\Telegraph\DTO\Message`

- `->id()` incoming _message_id_
- `->date()` a `CarbonInterface` holding the message sent date
- `->text()` the message text
- `->from()` (optional) an instance of [`DefStudio\Telegraph\DTO\User`](webhooks/dto#defstudio-telegraph-dto-user) holding data about the message's sender
- `->chat()` (optional) an instance of [`DefStudio\Telegraph\DTO\Chat`](webhooks/dto#defstudio-telegraph-dto-chat) holding data about the chat to which the message belongs to 
- `->keyboard()` (optional) an instance of [`DefStudio\Telegraph\Keyboard\Keyboard`](feature/keyboards) holding the message inline keyboard 


## `DefStudio\Telegraph\DTO\CallbackQuery`

- `->id()` incoming _callback_query_id_
- `->from()` (optional) an instance of the [`DefStudio\Telegraph\DTO\User`](webhooks/dto#defstudio-telegraph-dto-user) that triggered the callback query
- `->message()` (optional) an instance of the [`DefStudio\Telegraph\DTO\Message`](webhooks/dto#defstudio-telegraph-dto-message) that triggered the callback query
- `->data()` an `Illuminate\Support\Collection` that holds the key/value pairs of the callback query data


## `DefStudio\Telegraph\DTO\User`

- `->id()` user ID
- `->isBot()` marks if the user is a bot or a real user
- `->firstName()` user's first name 
- `->lastName()` user's last name 
- `->userName()` user's username 
