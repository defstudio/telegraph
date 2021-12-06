---
title: Installation & Configuration
menuTitle: 'Installation'
description: ''
position: 2
fullscreen: true
---

You can install the package via composer:

``` bash
composer require defstudio/telegraph
```

### Set up

In order to work, Telegraph needs you to run its migrations:

```bash
$> php artisan vendor:publish --tag="telegraph-migrations"

$> php artisan migrate
```

### Configuration

You can publish the config file with:

```bash
$> php artisan vendor:publish --tag="telegraph-config"
```

here's an example of what you'll find:

```php
return [
    /*
     * Sets Telegraph messages default parse mode
     * allowed values: html|markdown
     */
    'default_parse_mode' => Telegraph::PARSE_HTML,

    /*
     * Sets the handler to be used when Telegraph
     * receives a new webhook call.
     *
     * For reference, see https://def-studio.github.io/telegraph/webhooks/overview
     */
    'webhook_handler' => EmptyWebhookHandler::class,

    /*
     * If enabled, Telegraph dumps received
     * webhook messages to logs
     */
    'debug_mode' => false,

    /*
     * Message queue configuration
     */
    'queue' => [
        /*
         * Enables sending requests towards Telegram apis through Laravel's queue system
         */
        'enable' => true,

        /*
         * Sets the default queue to be used
         */
        'on_queue' => 'default',
    ],
];
```
