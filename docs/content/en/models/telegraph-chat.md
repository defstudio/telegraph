---
title: 'TelegraphChat'
description: ''
category: 'Models'
fullscreen: false 
position: 41
---

Chat informations are stored in database inside a `telegraph_chats` table and can be retrieved using `DefStudio\Telegraph\Models\TelegaphChat` model.

## Available methods

### `info()`

Retrieves the bot data from telegram

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

## message()

Starts a `Telegraph` call to send a message

```php
/** @var \DefStudio\Telegraph\Models\TelegraphChat $telegraphChat */

$telegraphChat->message('hello!')->send();
```

## html()

Starts a `Telegraph` call to send a message using html formatting

```php
/** @var \DefStudio\Telegraph\Models\TelegraphChat $telegraphChat */

$telegraphChat->html('<b>hello</b>')->send();
```

## markdown

Starts a `Telegraph` call to send a message using markdown formatting

```php
/** @var \DefStudio\Telegraph\Models\TelegraphChat $telegraphChat */

$telegraphChat->markdown('*hello*')->send();
```

## replaceKeyboard

Starts a `Telegraph` call to replace a message keyboard (see [keyboards](features/keyboards) for details)

```php
/** @var \DefStudio\Telegraph\Models\TelegraphChat $telegraphChat */

$telegraphChat->replaceKeyboard(
    $messageId, 
    Keyboard::make()->buttons([
        Button::make('open')->url('https://test.dev')
    ])
)->send();
```

## deleteKeyboard

Starts a `Telegraph` call to remove a message keyboard (see [keyboards](features/keyboards) for details)

```php
/** @var \DefStudio\Telegraph\Models\TelegraphChat $telegraphChat */

$telegraphChat->deleteKeyboard($messageId)->send();
```

<alert type="alert">Follow [installation](installation#set-up) instructions for creating the database tables</alert>

## deleteKeyboard

Starts a `Telegraph` call to delete a message

```php
/** @var \DefStudio\Telegraph\Models\TelegraphChat $telegraphChat */

$telegraphChat->deleteMessage($messageId)->send();
```
