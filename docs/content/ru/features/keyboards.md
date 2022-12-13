---
title: 'Клавиатура-сообщение' 
menuTitle: 'Клавиатура-сообщение' 
description: 'Мы можем отправить пользователю кнопки, вместо того, чтобы общаться текстом'
category: 'Особенности' 
fullscreen: false 
position: 32
---

Клавиатура может быть добавлена к сообщению, чтобы предложить пользователю выбор:

<img src="screenshots/keyboard-example.png" />

## Attaching a keyboard (прикрепление клавиатуры)

Клавиатура может быть добавлена, используя `->keyboard()` команду, передавая `Keyboard` новый объект как аргумент.

`Keyboard` может свободно определить свои кнопки и другие свойства (строки, фрагменты кнопок и т. д.):

Кнопки устанавливаются (добавляются) с помощью `Keyboard::make()->buttons()` метода, как массив из `Button`:

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

Telegraph::message('hello world')
->keyboard(Keyboard::make()->buttons([
        Button::make('Delete')->action('delete')->param('id', '42'),
        Button::make('open')->url('https://test.it'),
        Button::make('Web App')->webApp('https://web-app.test.it'),
]))->send();
```

А также, клавиатура может быть добавлена, используя функцию-замыкание (closure):

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

Telegraph::message('hello world')
->keyboard(function(Keyboard $keyboard){
    return $keyboard
        ->button('Delete')->action('delete')->param('id', '42')
        ->button('open')->url('https://test.it')
        ->button('Web App')->webApp('https://web-app.test.it');
})->send();
```

## Buttons (кнопки)

Каждая `Button` может быть определена методами и может быть трёх типов:

### Callback Buttons (кнопки обратного вызова)

Определяют свойство `action` и несколько аргументов `params`. Они обрабатываются как **запрос обратного вызова** в пользовательском WebHook:

```php
Button::make('Delete')->action('delete')->param('id', '42'),
```

### URL Buttons (кнопка-ссылка)

Определяют `url` и используются для открытия внешнего URL после нажатия:

```php
Button::make('open')->url('https://test.it'),
```

### Web App Buttons (кнопка веб-приложения)

Определяют для веб-приложения `url` и используются для открытия в Telegram Bot [Web App](https://core.telegram.org/bots/webapps)(веб-приложения):

```php
Button::make('open')->webApp('https://webapp.url.dev'),
```

### Switch Inline Query Buttons (переключить встроенные кнопки запросов)

Нажатие кнопки предложит пользователю выбрать один из своих чатов,
откройте этот чат и вставьте имя бота и указанный встроенный запрос
в поле ввода. Текст запроса может быть пустым, в этом случае будет вставлено 
имя бота. (смотрите `switch_inline_query` в [Telegram Bot docs](https://core.telegram.org/bots/api#inlinekeyboardbutton))


```php
Button::make('switch')->switchInlineQuery('foo'),
```

Дополнительно, кнопки встроенных запросов могут выполняться для текущего чата
(так, можно пропустить выбор чата) только если `->currentChat()` метод добавлен к кнопке
(см. `switch_inline_query_current_chat` в [Telegram Bot docs](https://core.telegram.org/bots/api#inlinekeyboardbutton))

```php
Button::make('switch')->switchInlineQuery('foo')->currentChat(),
```


## Keyboard Rows - строки клавиатуры

Стандартно клавиатура размещает в одной строке только одну кнопку, это можно изменить введением строк, устанавливая ширину кнопок или количество на строку.

### по строкам

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

$keyboard = Keyboard::make()
    ->row([
        Button::make('Delete')->action('delete')->param('id', '42'),
        Button::make('Dismiss')->action('dismiss')->param('id', '42'),
    ])
    ->row([
        Button::make('open')->url('https://test.it'),
    ]);
```

### по ширине кнопок

Кнопкам задают дробное число [0..1], указывающее процент занимаемого места. Кнопки будут занимать лишь доступную ширину строки, но при превышении максимальной ширины строки - перенесутся.

например, объявим две кнопки в первой строке и большую кнопку по второй:

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

$keyboard = Keyboard::make()
    ->button('Delete')->action('delete')->param('id', '42')->width(0.5)
    ->button('Dismiss')->action('dismiss')->param('id', '42')->width(0.5)
    ->button('open')->url('https://test.it');
    ->button('open')->webApp('https://web-app.url.dev');
```

**примечания**

 - стандартная ширина кнопки - 1 (то есть на всю ширину кнопки)
 - каждая ширина определяется дробным числом между 0 и 1, которое определяет процент занимаемого пространства в строке.
 - каждая кнопка будет заполнять строку, но при переполнении перенесётся на следующую строку

### разбиение по количеству в строке (by chunking)

Кнопки могут автоматически расположиться в строках, используя метод разбиения по количеству `->chunk()`.

Этот пример выведет первую строку с двумя кнопками, и вторую с двумя кнопками, но **последнюю с одной кнопкой**.

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

$keyboard = Keyboard::make()
    ->button('Delete')->action('delete')->param('id', '42')
    ->button('Dismiss')->action('dismiss')->param('id', '42')
    ->button('Share')->action('share')->param('id', '42')
    ->button('Solve')->action('solve')->param('id', '42')
    ->button('Open')->url('https://test.it')
    ->chunk(2);
```

## Обновление или замена клавиатуры

Сообщение с клавиатурой можно заменить, если указать `messageId` этого сообщения:

```php
Telegraph::replaceKeyboard(
    messageId: 1568794, 
    newKeyboard: Keyboard::make()->buttons([
        Button::make('Delete')->action('delete')->param('id', '42'),
        Button::make('open')->url('https://test.it'),
    ])
)->send();
```

## Удаление клавиатуры

Можно удалить сообщение с клавиатурой, зная `messageId`:

```php
Telegraph::deleteKeyboard(messageId: 1568794)->send();
```

## Методы с условиями, условный оператор

Метод `when` позволяет выполнить функцию-замыкание когда выполнено заданное условие:

```php
Keyboard::make()
    ->button('Dismiss')->action('dismiss')->param('id', '42')->width(0.5)
    ->when($userCanDelete, fn(Keyboard $keyboard) => $keyboard->button('Delete')->action('delete')->param('id', '42')->width(0.5))
```
