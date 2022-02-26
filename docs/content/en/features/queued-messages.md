---
title: 'Queued Messages'
menuTitle: 'Queued Messages'
description: ''
category: 'Features'
fullscreen: false 
position: 34
---

A `->dispatch()` method can be used to have Telegraph to interact with telegraph through the Laravel queue system:


```php
Telegraph::message('hello')->dispatch();
```


optionally a queue name can be hinted: `->dispatch('my queue')`
