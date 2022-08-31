<?php

namespace DefStudio\Telegraph\Enums;

final class ChatPermissions
{
    public const CAN_SEND_MESSAGES = 'can_send_messages';
    public const CAN_SEND_MEDIA_MESSAGES = 'can_send_media_messages';
    public const CAN_SEND_POLLS = 'can_send_polls';
    public const CAN_SEND_OTHER_MESSAGES = 'can_send_other_messages';
    public const CAN_ADD_WEB_PAGE_PREVIEWS = 'can_add_web_page_previews';
    public const CAN_CHANGE_INFO = 'can_change_info';
    public const CAN_INVITE_USERS = 'can_invite_users';
    public const CAN_PIN_MESSAGES = 'can_pin_messages';

    /**
     * @return string[]
     */
    public static function available_permissions(): array
    {
        $reflection = new \ReflectionClass(self::class);

        /* @phpstan-ignore-next-line  */
        return $reflection->getConstants();
    }
}
