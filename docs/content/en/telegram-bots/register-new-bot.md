---
title: 'Registering a bot with Telegraph'
menuTitle: 'Adding bots to Telegraph'
description: ''
category: 'Telegram Bots'
fullscreen: false 
position: 21
---

## Registering the newly created bot into your application

Any number of bots can be created, both programmatically and through an artisan command

### through artisan command

You can add a new bot issuing the dedicated _artisan_ command:

```shell
php artisan telegraph:new-bot
```
you will be guided through a bot creation wizard that will (optionally) allow you to add a new chat and setup a bot webhook as well

<img src="screenshots/artisan-new-bot.jpg" />

### programmatically

If you are implementing a custom bot creation logic, you can create a new bot using the `TelegraphBot` model:

```php
$bot = TelegraphBot::create([
    'token' => $token,
    'name' => $name,
]);
```
