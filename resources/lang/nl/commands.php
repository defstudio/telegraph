<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    "new_bot" => [
        'starting_message' => 'Nieuwe Telegram Bot maken',
        'enter_bot_token' => 'Voer het bot token in',
        'enter_bot_name' => 'Voer de naam van de bot in (optioneel)',
        'ask_to_add_a_chat' => 'Wil je een chat toevoegen aan deze bot?',
        'ask_to_setup_webhook' => 'Wil je een webhook instellen voor deze bot?',
        'bot_created' => 'Nieuwe bot :bot_name is aangemaakt',
    ],

    'new_chat' => [
        'starting_message' => "Nieuwe Telegram Chat maken voor bot :bot_name",
        'enter_chat_id' => 'Voer het chat ID in - druk [x] om te annuleren',
        'enter_chat_name' => 'Voer de naam van de chat in (optioneel)',
        'chat_created' => 'Nieuwe chat :chat_name is gemaakt voor :bot_name',
    ],

    'set_webhook' => [
        'sending_setup_request' => 'Webhook setup verzoek versturen naar: :api_url',
        'webhook_updated' => 'Webhook aangepast'
    ],

    'unset_webhook' => [
        'sending_unset_request' => 'Webhook verwijder verzoek versturen naar: :api_url',
        'webhook_deleted' => 'Webhook verwijderd'
    ],
];
