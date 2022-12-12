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

## Attaching a keyboard - прикрепление клавиатуры

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

## Buttons - кнопки

Каждая `Button` может быть определена методами и может быть трёх типов:

### Callback Buttons - кнопки обратного вызова

Определяют свойство `action` и несколько аргументов `params`. Они обрабатываются как **запрос обратного вызова** в пользовательском WebHook:

```php
Button::make('Delete')->action('delete')->param('id', '42'),
```

### URL Buttons - кнопка-ссылка

Определяют `url` и используются для открытия внешнего URL после нажатия:

```php
Button::make('open')->url('https://test.it'),
```

### Web App Buttons - кнопка веб-приложения

Определяют для веб-приложения `url` и используются для открытия в Telegram Bot [Web App](https://core.telegram.org/bots/webapps)(веб-приложения):

```php
Button::make('open')->webApp('https://webapp.url.dev'),
```

### Switch Inline Query Buttons

Pressing the button will prompt the user to select one of their chats, 
open that chat and insert the bot's username and the specified inline query 
in the input field. The query text may be empty, in which case just the 
bot's username will be inserted. (see `switch_inline_query` in [Telegram Bot docs](https://core.telegram.org/bots/api#inlinekeyboardbutton) for reference)


```php
Button::make('switch')->switchInlineQuery('foo'),
```

Additionally, an inline query button may act for the current chat
(so, would skip the destination chat prompt) only if `->currentChat()` method is added
(see `switch_inline_query_current_chat` in [Telegram Bot docs](https://core.telegram.org/bots/api#inlinekeyboardbutton) for reference)

```php
Button::make('switch')->switchInlineQuery('foo')->currentChat(),
```


## Keyboard Rows

A keyboard will normally place one button per row, this behaviour can be customized by defining rows, by setting individual buttons width or by chunking buttons

### by rows

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

### by setting buttons width

A button relative width can be set using a float number the total width percentage to be taken. Buttons will flow through the rows according to their width

this example would define two buttons on the first row and a large button on the second one:

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

$keyboard = Keyboard::make()
    ->button('Delete')->action('delete')->param('id', '42')->width(0.5)
    ->button('Dismiss')->action('dismiss')->param('id', '42')->width(0.5)
    ->button('open')->url('https://test.it');
    ->button('open')->webApp('https://web-app.url.dev');
```

**notes**

 - A button default width is 1 (that's to say, the entire row width)
 - Each width is defined as a float between 0 and 1 that represents the floating point percentage of the row taken by the button.
 - each button will fill the current row or flow in the subsequent one if there isn't enough space left

### by chunking

Buttons can be authomatically chunked in rows using the `->chunk()` method.

This example would return a first row of two buttons, a second row of two buttons and the last row with the remaining button.

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

## Updating a keyboard

A message keyboard can be replaced by a new one by submitting its `messageId`:

```php
Telegraph::replaceKeyboard(
    messageId: 1568794, 
    newKeyboard: Keyboard::make()->buttons([
        Button::make('Delete')->action('delete')->param('id', '42'),
        Button::make('open')->url('https://test.it'),
    ])
)->send();
```

## Deleting a keyboard

A keyboard can be removed from a message by submitting its `messageId`:

```php
Telegraph::deleteKeyboard(messageId: 1568794)->send();
```

## Conditional methods

a `when` method allows to execute a closure when the given condition is verified

```php
Keyboard::make()
    ->button('Dismiss')->action('dismiss')->param('id', '42')->width(0.5)
    ->when($userCanDelete, fn(Keyboard $keyboard) => $keyboard->button('Delete')->action('delete')->param('id', '42')->width(0.5))
```
