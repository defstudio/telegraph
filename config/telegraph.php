<?php

use DefStudio\Telegraph\Handlers\EmptyWebhookHandler;
use DefStudio\Telegraph\Telegraph;

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
