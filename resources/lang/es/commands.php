<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    "new_bot" => [
        'starting_message' => 'Estás a punto de crear un nuevo Bot de Telegram',
        'enter_bot_token' => 'Por favor, introduce el token del bot',
        'enter_bot_name' => 'Introduce el nombre del bot (opcional)',
        'ask_to_add_a_chat' => '¿Quieres agregar un chat a este bot?',
        'ask_to_setup_webhook' => '¿Desea configurar un webhook para este bot?',
        'bot_created' => 'Se ha creado un nuevo bot :bot_name',
    ],

    'new_chat' => [
        'starting_message' => "Está a punto de crear un nuevo chat de Telegram para el bot :bot_name",
        'enter_chat_id' => 'Ingrese el ID del chat - presione [x] para cancelar',
        'enter_chat_name' => 'Introduce el nombre del chat (opcional)',
        'chat_created' => 'Se ha creado un nuevo chat :chat_name para el bot :bot_name',
    ],

    'set_webhook' => [
        'sending_setup_request' => 'Enviando solicitud de configuración de webhook a: :api_url',
        'webhook_updated' => 'Webhook actualizado'
    ],

    'unset_webhook' => [
        'sending_unset_request' => 'Enviando solicitud de anulación de webhook a: :api_url',
        'webhook_deleted' => 'Webhook eliminado'
    ],
];
