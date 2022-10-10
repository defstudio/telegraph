<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    "new_bot" => [
        'starting_message' => 'You are about to create a new Telegram Bot',
        'enter_bot_token' => 'Please, enter the bot token',
        'enter_bot_name' => 'Enter the bot name (optional)',
        'ask_to_add_a_chat' => 'Do you want to add a chat to this bot?',
        'ask_to_setup_webhook' => 'Do you want to setup a webhook for this bot?',
        'bot_created' => 'New bot :bot_name has been created',
    ],

    'new_chat' => [
        'starting_message' => "You are about to create a new Telegram Chat for bot :bot_name",
        'enter_chat_id' => 'Enter the chat ID - press [x] to abort',
        'enter_chat_name' => 'Enter the chat name (optional)',
        'chat_created' => 'New chat :chat_name has been create for bot :bot_name',
    ],

    'set_webhook' => [
        'sending_setup_request' => 'Sending webhook setup request to: :api_url',
        'webhook_updated' => 'Webhook updated'
    ],

    'unset_webhook' => [
        'sending_unset_request' => 'Sending webhook unset request to: :api_url',
        'webhook_deleted' => 'Webhook deleted'
    ],
];
