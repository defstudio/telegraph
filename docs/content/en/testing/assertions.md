---
title: 'Fake messages assertions'
menuTitle: 'Assertions'
description: ''
category: 'Testing'
fullscreen: false 
position: 51
---

The `::fake()` facade helper enables a number of assertions for checking its activity

## assertSent

asserts that a message was sent to Telegram with the given text

```php
//will check that the sent text is exactly 'hello'
Telegraph::assertSent('hello');

//will check that the sent text contains 'hello'
Telegraph::assertSent('hello', false);
```

## assertNothingSent

asserts that no messages where sent to Telegram

```php
Telegraph::assertNothingSent();
```

## assertSentData

asserts that the given data was sent to a Telegram API endpoint

```php
Telegraph::assertSentData(
    DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE, 
    ['text' => 'foo bar baz']
);

Telegraph::assertSentData(
    DefStudio\Telegraph\Telegraph::ENDPOINT_REPLACE_KEYBOARD,
    [
        'chat_id' => -546874,
        'message_id' => 42,
        'reply_markup' => null,
    ]
);
```

## assertRegisteredWebhook

asserts that a webhook register request has been sent

```php
Telegraph::assertRegisteredWebhook();
```

## assertRequestedWebhookDebugInfo

asserts that a webhook debug info request has been sent

```php
Telegraph::assertRequestedWebhookDebugInfo();
```

## assertRepliedWebhook

asserts that a webhook reply has been sent with the given text

```php
Telegraph::assertRepliedWebhook('task completed');
```

