<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language lines used in console commands
    |--------------------------------------------------------------------------
    */

    "new_bot" => [
        'starting_message' => 'أنت على وشك إنشاء روبوت تيليجرام جديد',
        'enter_bot_token' => 'من فضلك، قم بإدخال توكن الروبوت',
        'enter_bot_name' => 'أدخل اسم الروبوت (اختياري)',
        'ask_to_add_a_chat' => 'هل ترغب في إضافة محادثة لهذا الروبوت؟',
        'ask_to_setup_webhook' => 'هل ترغب في إعداد خطاف (Webhook) لهذا الروبوت؟',
        'bot_created' => 'تم إنشاء الروبوت الجديد :bot_name',
    ],


    'new_chat' => [
        'starting_message' => 'أنت على وشك إنشاء محادثة تيليجرام جديدة للروبوت :bot_name',
        'enter_chat_id' => 'أدخل معرف المحادثة - اضغط [x] للإلغاء',
        'enter_chat_name' => 'أدخل اسم المحادثة (اختياري)',
        'chat_created' => 'تم إنشاء محادثة جديدة :chat_name للروبوت :bot_name',
    ],


    'set_webhook' => [
        'sending_setup_request' => 'جارٍ إرسال طلب إعداد الخطاف (Webhook) إلى: :api_url',
        'webhook_updated' => 'تم تحديث الخطاف (Webhook)'
    ],


    'unset_webhook' => [
        'sending_unset_request' => 'جارٍ إرسال طلب إلغاء الخطاف (Webhook) إلى: :api_url',
        'webhook_deleted' => 'تم حذف الخطاف (Webhook)'
    ],

];
