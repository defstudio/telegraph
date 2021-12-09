---
title: 'Webhooks Overview'
menuTitle: 'Overview'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 40
---

Telegram bots can interact with chats and users through a webhook system that enables it to be updated about chats changes, new commands and user interactions without continuously polling Telegram APIs for updates.

## Default Handler

A default "do nothing" handler is shipped with Telegraph installation, it can only handle a single chat command:

```
/chatid
```

And answers with the ID of the chat the command is issued into. It is useful to get the ChatID in order to register a new chat in Telegraph
