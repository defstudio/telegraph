<?php

use DefStudio\Telegraph\Handlers\EmptyWebhookHandler;

return [
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID'),

    /*
     * html|markdown
     */
    'default_parse_mode' => 'html',

    'webhook_handler' => EmptyWebhookHandler::class,

    /*
     * If enabled, dump received webhook message to logs
     */
    'debug_mode' => false,
];
