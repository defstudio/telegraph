<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    "new_bot" => [
        'starting_message' => '您即將建立一個新的 Telegram Bot',
        'enter_bot_token' => '請輸入機器人令牌',
        'enter_bot_name' => '請輸入機器人名稱（選填）',
        'ask_to_add_a_chat' => '您要新增一個和此機器人的聊天嗎？',
        'ask_to_setup_webhook' => '您想為此機器人設定一個 webhook 嗎？',
        'bot_created' => '新機器人 :bot_name 已經建立',
    ],

    'new_chat' => [
        'starting_message' => "您即將為機器人 :bot_name 新建一個 Telegram 聊天",
        'enter_chat_id' => '請輸入聊天 ID - 按下 [x] 以取消',
        'enter_chat_name' => '請輸入聊天名稱（選填）',
        'chat_created' => '和機器人 :bot_name 的新聊天 :chat_name 已建立',
    ],

    'set_webhook' => [
        'sending_setup_request' => '送出 webhook 設定請求至： :api_url',
        'webhook_updated' => 'Webhook 已更新'
    ],

    'unset_webhook' => [
        'sending_unset_request' => '送出 webhook 取消請求至： :api_url',
        'webhook_deleted' => 'Webhook 已刪除'
    ],
];
