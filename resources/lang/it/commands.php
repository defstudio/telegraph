<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    "new_bot" => [
        'starting_message' => 'Creazione di un nuovo Telegram Bot',
        'enter_bot_token' => 'Inserisci il token del bot',
        'enter_bot_name' => 'Inserisci il nome del bot (opzionale)',
        'ask_to_add_a_chat' => 'Desideri configurare una chat per questo bot?',
        'ask_to_setup_webhook' => 'Desideri configurare un webhook per questo bot?',
        'bot_created' => 'Nuovo bot creato: :bot_name',
    ],

    'new_chat' => [
        'starting_message' => "Creazione di una nuova Telegram Chat per il bot :bot_name",
        'enter_chat_id' => "Inserisci l'ID della chat - premi [x] per annullare",
        'enter_chat_name' => 'Inserisci il nome della chat (opzionale)',
        'chat_created' => 'Nuova chat :chat_name creata per il bot :bot_name',
    ],

    'set_webhook' => [
        'sending_setup_request' => 'Invio della richiesta di setup del webook a: :api_url',
        'webhook_updated' => 'Webhook aggiornato'
    ],

    'unset_webhook' => [
        'sending_unset_request' => 'Invio della richiesta di cancellazione del webhook a: :api_url',
        'webhook_deleted' => 'Webhook eliminato'
    ],
];
