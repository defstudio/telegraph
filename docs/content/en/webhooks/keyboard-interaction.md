---
title: 'Interact With The Chat Keyboard'
menuTitle: 'Keyboard Interaction'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 44
---


The keyboard that triggered the callback can be retrieved through the `$originalKeyboard` property, an `Illuminate\Support\Collection` instance that holds the keyboard buttons:

```php
class CustomWebhookHandler extends WebhookHandler
{
    public function dismiss(){
        //...
        
        $this->originalKeyboard; 
        
        //Collection: [
        //  ["text" => "Delete", "callback_data" => "action:delete;notification-id:42"],
        //  ["text" => "Dismiss", "callback_data" => "action:dismiss;notification-id:42"],
        //]
    }
}
```


and can be manipulated with some dedicated methods:

### replaceKeyboard

The entire keyboard can be replaced using the `->replaceKeyboard()` method:

```php
class CustomWebhookHandler extends WebhookHandler
{
    public function dismiss(){
        //...
        
        $newKeyboard = $this->originalKeyboard
            ->reject(fn (array $button) => str($button['callback_data'])->startsWith('action:dismiss'))
            ->values()
            ->chunk(2); 
        
        $this->replaceKeyboard($newKeyboard);
    }
}
```

### deleteKeyboard

The keyboard can be removed using the `->deleteKeyboard()` method:

```php
class CustomWebhookHandler extends WebhookHandler
{
    public function dismiss(){
        //...
        
        $this->deleteKeyboard();
    }
}
```
