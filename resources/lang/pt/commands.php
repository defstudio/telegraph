<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    "new_bot" => [
        'starting_message' => 'Você está prestes a criar um novo bot do Telegram',
        'enter_bot_token' => 'Por favor, introduza o token do bot',
        'enter_bot_name' => 'Entre o nome do bot (opcional)',
        'ask_to_add_a_chat' => 'Deseja adicionar um chat a este bot?',
        'ask_to_setup_webhook' => 'Deseja configurar um webhook para este bot?',
        'bot_created' => 'Foi criado um novo bot :bot_name',
    ],

    'new_chat' => [
        'starting_message' => "Você está prestes a criar um novo chat do Telegram para o :bot_name",
        'enter_chat_id' => 'Insira o ID do chat - pressione [x] para cancelar',
        'enter_chat_name' => 'Entre o nome do chat (opcional)',
        'chat_created' => 'Novo chat :chat_name foi criado para o bot :bot_name',
    ],

    'set_webhook' => [
        'sending_setup_request' => 'Enviando pedido de configuração do webhook para: :api_url',
        'webhook_updated' => 'Webhook atualizado'
    ],

    'unset_webhook' => [
        'sending_unset_request' => 'Enviando pedido de desativação do webhook para: :api_url',
        'webhook_deleted' => 'Webhook apagado'
    ],
];
