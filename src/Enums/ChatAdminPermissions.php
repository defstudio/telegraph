<?php

namespace DefStudio\Telegraph\Enums;

final class ChatAdminPermissions
{
    public const IS_ANONYMOUS = 'is_anonymous';
    public const CAN_MANAGE_CHAT = 'can_manage_chat';
    public const CAN_DELETE_MESSAGES = 'can_delete_messages';
    public const CAN_MANAGE_VIDEO_CHATS = 'can_manage_video_chats';
    public const CAN_RESTRICT_MEMBERS = 'can_restrict_members';
    public const CAN_PROMOTE_MEMBERS = 'can_promote_members';
    public const CAN_CHANGE_INFO = 'can_change_info';
    public const CAN_INVITE_USERS = 'can_invite_users';
    public const CAN_POST_STORIES = 'can_post_stories';
    public const CAN_EDIT_STORIES = 'can_edit_stories';
    public const CAN_DELETE_STORIES = 'can_delete_stories';
    public const CAN_POST_MESSAGES = 'can_post_messages';
    public const CAN_EDIT_MESSAGES = 'can_edit_messages';
    public const CAN_PIN_MESSAGES = 'can_pin_messages';
    public const CAN_MANAGE_TOPICS = 'can_manage_topics';

    /**
     * @return string[]
     */
    public static function available_permissions(): array
    {
        $reflection = new \ReflectionClass(self::class);

        /* @phpstan-ignore-next-line */
        return $reflection->getConstants();
    }
}
