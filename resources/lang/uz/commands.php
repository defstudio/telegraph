<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Language lines used in console commands
  |--------------------------------------------------------------------------
  */

  "new_bot" => [
    'starting_message'     => 'Siz yangi Telegram bot yaratmoqchimisiz?',
    'enter_bot_token'      => 'Iltimos, bot tokenini kiriting',
    'enter_bot_name'       => 'Bot nomini kiriting (ixtiyoriy)',
    'ask_to_add_a_chat'    => 'Siz botni chatga qo’shmoqchimisiz?',
    'ask_to_setup_webhook' => 'Siz webhook sozlamalarini kiritmoqchimisz?',
    'bot_created'          => ':bot_name Bot muvaffaqiyatli yaratildi',
  ],

  'new_chat' => [
    'starting_message' => 'Siz :bot_name Telegram-boti uchun yangi chat yaratmoqchimisiz',
    'enter_chat_id'    => 'Chat ID-ni kiriting - bekor qilish uchun [x] tugmasini bosing',
    'enter_chat_name'  => 'Chat nomini kiriting (ixtiyoriy)',
    'chat_created'     => ':chat_name chat muvaffaqiyatli yaratildi va :bot_name botga qo’shildi',
  ],

  'set_webhook' => [
    'sending_setup_request' => 'Webhookni o’rnatish uchun :api_url ga so’rov yuborilmoqda',
    'webhook_updated'       => 'Webhook yangilandi',
  ],

  'unset_webhook' => [
    'sending_unset_request' => 'Webhookni o’chirish uchun :api_url ga so’rov yuborilmoqda',
    'webhook_deleted'       => 'Webhook o’chirildi',
  ],
];
