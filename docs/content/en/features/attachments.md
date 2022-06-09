---
title: 'Attachments' 
menuTitle: 'Attachments' 
description: ''
category: 'Features' 
fullscreen: false 
position: 36
---

Telegraph supports different types of attachments

## Attachment types

### Photos

Photos can be sent through Telegraph `->photo()` method:

```php
Telegraph::photo(Storage::path('photo.jpg'))->send();
```


### Documents

Documents can be sent through Telegraph `->document()` method:

```php
Telegraph::document(Storage::path('my_document.pdf'))->send();
```

### Location

A location attachment can be sent through Telegraph `->location()` method:

```php
Telegraph::location(12.345, -54.321)->send();
```

## Options

When sending files, some options are available:

### Html caption

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->html('<b>read this</b>')
    ->send();
```

### Markdown caption

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->markdown('read *this*')
    ->send();
```

### Without notification

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->silent()
    ->send();
```

### Prevent sharing

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->protected()
    ->send();
```

### Reply to a message

```php
Telegraph::document(Storage::path('my_document.pdf'))
    ->reply($messageId)
    ->send();
```

### Attach a keyboard

```php
Telegraph::document(Storage::path('brochure.pdf'))
      ->keyboard(fn (Keyboard $keyboard) => $keyboard->button('visit')->url('https://defstudio.it'))
    ->send();
```

### Add a thumbnail

```php
Telegraph::document(Storage::path('brochure.pdf'))
    ->thumbnail(Storage::path('brochure_thumbnail.jpg'))
    ->send();
```
