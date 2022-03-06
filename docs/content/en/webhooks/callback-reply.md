---
title: 'Reply To A Callback Query'
menuTitle: 'Callback Reply'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 63
---


A visual feedback can be returned to the chat as a tooltip with the `->reply()` method:

```php
class CustomWebhookHandler extends WebhookHandler
{
    public function dismiss(){
        //...
        
        $this->reply("Notification dismissed")->send();
    }
}
```


