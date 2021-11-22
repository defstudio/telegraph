<?php

use DefStudio\Telegraph\Handlers\EmptyWebhookHandler;
use DefStudio\Telegraph\Telegraph;

return [
    /*
     * html|markdown
     */
    'default_parse_mode' => Telegraph::PARSE_HTML,

    'webhook_handler' => EmptyWebhookHandler::class,

    /*
     * If enabled, dump received webhook message to logs
     */
    'debug_mode' => false,
];
