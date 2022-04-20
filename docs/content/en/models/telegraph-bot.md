---
title: 'TelegraphBot'
description: ''
category: 'Models'
fullscreen: false 
position: 40
---

Bot informations are stored in database inside a `telegraph_bots` table and can be retrieved using `DefStudio\Telegraph\Models\TelegaphBot` model or using a custom Bot model.

## Custom Bot Model

To customize on your own Bot model, make sure that your custom model extends the `DefStudio\Telegraph\Models\TelegraphBot`, e.g. `App\Models\Bot`, it will looks like this:

```php
<?php

namespace App\Models;

use DefStudio\Telegraph\Models\TelegraphBot as BaseModel;

class Bot extends BaseModel
{
    
}
```

You should specify the class name of your model in the `models.bot` key of the telegraph config file.

```php
'models' => [
    'bot' => App\Models\Bot::class,
],
```

## Available methods

### `url()`

retrieves the bot url

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->url();

// https://t.me/my-bot-name
```

### `info()`

retrieves the bot information from Telegraph APIs

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->info();


/*
id: xxxxx
is_bot: true
first_name: telegraph-test
username: my_test_bot
can_join_groups: true
can_read_all_group_messages: false
supports_inline_queries: false
*/
```

### `registerCommands()`

register commands in Telegram Bot in order to display them to the user when the "/" key is pressed

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->registerCommands([
    'command1' => 'command 1 description',
    'command2' => 'command 2 description'
])->send();
```

### `unregisterCommands()`

resets Telegram Bot registered commands

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->unregisterCommands()->send();
```

### `registerWebhook()`

register a webhook url

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->registerWebhook()->send();
```

### `getWebhookDebugInfo()`

retrieves webhook debug data

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->getWebhookDebugInfo()->send();
```

### `replyWebhook()`

replies to a webhook callback

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

$telegraphBot->replyWebhook($callbackQueryId, 'message received')->send();
```


### `updates()`

Retrieves the Bot message and callback query updates using [manual polling](webhooks/manual-polling)

```php
/** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */

use DefStudio\Telegraph\DTO\TelegramUpdate;$telegraphBot->updates()->each(function(TelegramUpdate $update){
    // ...
});
```





<alert type="alert">Follow [installation](installation#set-up) instructions for creating the database tables</alert>

