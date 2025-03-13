<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    "new_bot" => [
        'starting_message' => 'شما در حال ایجاد یک ربات تلگرام جدید هستید',
        'enter_bot_token' => 'لطفاً توکن ربات را وارد کنید',
        'enter_bot_name' => 'نام ربات را وارد کنید (اختیاری)',
        'ask_to_add_a_chat' => 'آیا می‌خواهید یک چت به این ربات اضافه کنید؟',
        'ask_to_setup_webhook' => 'آیا می‌خواهید یک وب‌هوک برای این ربات تنظیم کنید؟',
        'bot_created' => 'ربات جدید :bot_name ایجاد شد',
    ],

    'new_chat' => [
        'starting_message' => "شما در حال ایجاد یک چت تلگرام جدید برای ربات :bot_name هستید",
        'enter_chat_id' => 'شناسه چت را وارد کنید - برای لغو [x] را فشار دهید',
        'enter_chat_name' => 'نام چت را وارد کنید (اختیاری)',
        'chat_created' => 'چت جدید :chat_name برای ربات :bot_name ایجاد شد',
    ],

    'set_webhook' => [
        'sending_setup_request' => 'در حال ارسال درخواست تنظیم وب‌هوک به: :api_url',
        'webhook_updated' => 'وب‌هوک به‌روزرسانی شد'
    ],

    'unset_webhook' => [
        'sending_unset_request' => 'در حال ارسال درخواست حذف وب‌هوک به: :api_url',
        'webhook_deleted' => 'وب‌هوک حذف شد'
    ],
];
