<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    'new_bot' => [
        'starting_message' => 'Sie sind dabei, einen neuen Telegram Bot zu erstellen',
        'enter_bot_token' => 'Bitte geben Sie den Bot-Token ein',
        'enter_bot_name' => 'Geben Sie den Bot-Namen ein (optional)',
        'ask_to_add_a_chat' => 'Möchten Sie einen Chat zu diesem Bot hinzufügen?',
        'ask_to_setup_webhook' => 'Möchten Sie einen Webhook für diesen Bot einrichten?',
        'bot_created' => 'Neuer Bot :bot_name wurde erstellt',
    ],

    'new_chat' => [
        'starting_message' => 'Sie sind dabei, einen neuen Telegram Chat für den Bot :bot_name zu erstellen',
        'enter_chat_id' => 'Bitte geben Sie die Chat-ID ein - drücken Sie [x] zum Abbrechen',
        'enter_chat_name' => 'Geben Sie den Chat-Namen ein (optional)',
        'chat_created' => 'Neuer Chat :chat_name wurde für den Bot :bot_name erstellt',
    ],

    'set_webhook' => [
        'sending_setup_request' => 'Sende Webhook-Einreichungsanfrage an: :api_url',
        'webhook_updated' => 'Webhook aktualisiert',
    ],

    'unset_webhook' => [
        'sending_unset_request' => 'Sende Webhook-Entfernanfrage an: :api_url',
        'webhook_deleted' => 'Webhook entfernt',
    ],
];
