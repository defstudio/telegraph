---
title: 'Вложения' 
menuTitle: 'Вложения' 
description: 'Это конкрент, который мы можем прикрепить к сообщению'
category: 'Особенности' 
fullscreen: false 
position: 36
---

Telegraph поддерживает разные типы вложений, находящиеся в локальных файлах, доступных по URL и удалённые файлы на сереверах Telegram (используя их file_id)

## Типы вложений

### Photos - фотографии

Фотографии могут быть отправлены через Telegraph с помощью `->photo()` метода:

```php
Telegraph::photo(Storage::path('photo.jpg'))->send();
Telegraph::photo('https://my-repository/photo.jpg')->send();
Telegraph::photo($telegramFileId)->send();
```


### Vocal Messages - голосовые сообщения

Голосовые сообщения могут быть отправлены через Telegraph с помощью `->voice()` метода:

```php
Telegraph::voice(Storage::path('voice.ogg'))->send();
Telegraph::voice('https://my-repository/voice.ogg')->send();
Telegraph::voice($telegramFileId)->send();
```


### Documents - документы

Документы могут быть отправлены через Telegraph с помощью `->document()` метода:

```php
Telegraph::document(Storage::path('my_document.pdf'))->send();
Telegraph::document('https://my-repository/my_document.pdf')->send();
Telegraph::document($telegramFileId)->send();
```

### Location - GPS-местонахождение

Местонахождение может быть отправлено через Telegraph с помощью `->location()` метода:

```php
Telegraph::location(12.345, -54.321)->send();
```

### Dice - анимированные эмодзи

Анимированный эмодзи, который отобразит случайное значение, может быть отправлен через Telegraph с помощью `->dice()` метода:

```php
Telegraph::dice()->send();
```

Разные эмодзи могут быть использованы как "dice"

```php
Telegraph::dice(\DefStudio\Telegraph\Enums\Emojis::SLOT_MACHINE)->send();
```

## Options - настройки

Когда отправляете файлы - вам доступны некоторые настройки:

### Html caption - HTML-описание

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->html('<b>read this</b>')
    ->send();
```

<alert type="alert">Sent attachment captions can be edited with the [editCaption](features/telegram-api-calls#editCaption) call</alert>


### Markdown caption - Markdown-описание

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->markdown('read *this*')
    ->send();
```

<alert type="alert">Sent attachment captions can be edited with the [editCaption](features/telegram-api-calls#editCaption) call</alert>


### MarkdownV2 caption - MarkownV2 описание

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->markdownV2('read *this*')
    ->send();
```

<alert type="alert">Sent attachment captions can be edited with the [editCaption](features/telegram-api-calls#editCaption) call</alert>


### Without notification - без оповещений

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->silent()
    ->send();
```

### Prevent sharing - безопасно отправить вложение

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->protected()
    ->send();
```

### Reply to a message - ответить на сообщение

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->reply($messageId)
    ->send();
```

### Attach a keyboard - прикрепить Keyboard

```php
Telegraph::document(Storage::path('brochure.pdf'))
      ->keyboard(fn (Keyboard $keyboard) => $keyboard->button('visit')->url('https://defstudio.it'))
    ->send();
```

### Add a thumbnail - добавить изображение

```php
Telegraph::document(Storage::path('brochure.pdf'))
    ->thumbnail(Storage::path('brochure_thumbnail.jpg'))
    ->send();
```
