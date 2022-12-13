---
title: 'Очереди Сообщений'
menuTitle: 'Очереди сообщений'
description: 'Сообщения можно выстраивать в очередь'
category: 'Особенности'
fullscreen: false 
position: 35
---

`->dispatch()` метод может использоваться в Telegraph для работы с Laravel системой очередей:


```php
Telegraph::message('hello')->dispatch();
```


дополнительно, очередь можно обозвать: `->dispatch('моя крутая очередь')`
