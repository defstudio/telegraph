---
title: 'Messages'
menuTitle: 'Messages'
description: ''
category: 'Features'
fullscreen: false 
position: 30
---

Сообщения можно отравить, используя `TelegraphChat` модель

```php
use DefStudio\Telegraph\Models\TelegraphChat;

$chat = TelegraphChat::find(44);

// будет использовать стандартную настройку из config/telegraph.php
$chat->message('hello')->send();

$chat->html("<b>hello</b>\n\nI'm a bot!")->send();

$chat->markdown('*hello*')->send();
```

## Настройки, опции

Telegraph позволяет отправить сложные сообщения, устанавливая некоторые настройки:

### edit (редактирование)

Обновить существующее сообщение, вместо оправки нового:


```php
$chat->edit(123456)->message("новый текст")->send();
```

### reply (ответ)

Сообщение можно отправить в ответ на другое, указав ID сообщения, на которое даётся ответ.

```php
$chat->message("ok!")->reply(123456)->send();
```

### forceReply (обязательный ответ)

Заставляет пользователя ответить на сообщение. Больше информации в [the official api documentation](https://core.telegram.org/bots/api#forcereply)

```php
$chat->message("ok!")->forceReply(placeholder: 'Введите ваш ответ...')->send();
```

### protected (защищённые)

Защитит контент сообщения от пересылок и сохранения

```php
$chat->message("please don't share this")->protected()->send();
```

### silent (без уведомлений)

Отравит сообщение [silently](https://telegram.org/blog/channels-2-0#silent-messages). Пользователи не получат уведомления.

```php
$chat->message("late night message")->silent()->send();
```

### withoutPreview (без предпросмотра ссылок)

Disables link previews for links in this message
Отключает у ссылок вывод предпросмотра в этом сообщении.

```php
$chat->message("http://my-blog.dev")->withoutPreview()->send();
```

## Delete a message (удалить сообщение)

[`->deleteMessage()`](features/telegram-api-calls/delete-message) Telegraph метод удалит сообщение из чата/группы/канала

<alert type="alert">Сообщение можно удалить не позднее, чем через 48 часов с **момента отправки** от бота, но если бот **имеет права** на удаление сообщений других пользователей, то ограничений по времени нет.</alert>
