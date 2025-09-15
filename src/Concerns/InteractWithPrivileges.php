<?php


/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\ChatMember;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */

trait InteractWithPrivileges
{
    private bool $canBeEdited = false;
    private bool $canChangeInfo = false;
    private bool $canInviteUsers = false;
    private bool $canManageChat = false;
    private bool $canManageTopics = false;
    private bool $canManageVideoChats = false;
    private bool $canManageVoiceChats = false;
    private bool $canManageDirectMessages = false;
    private bool $canRestrictMembers = false;
    private bool $canPromoteMembers = false;
    private bool $canPostMessages = false;
    private bool $canEditMessages = false;
    private bool $canDeleteMessages = false;
    private bool $canPinMessages = false;
    private bool $canPostStories = false;
    private bool $canEditStories = false;
    private bool $canDeleteStories = false;
    private bool $canSendMessages = false;
    private bool $canSendMediaMessages = false;
    private bool $canSendAudios = false;
    private bool $canSendDocuments = false;
    private bool $canSendPhotos = false;
    private bool $canSendVideos = false;
    private bool $canSendVideoNotes = false;
    private bool $canSendVoiceNotes = false;
    private bool $canSendPolls = false;
    private bool $canSendOtherMessages = false;
    private bool $canAddWebPagePreviews = false;

    /**
     * @param array{
     *     can_be_edited?:bool,
     *     can_change_info?:bool,
     *     can_invite_users?:bool,
     *     can_manage_chat?:bool,
     *     can_manage_topics?:bool,
     *     can_manage_video_chats?:bool,
     *     can_manage_voice_chats?:bool,
     *     can_manage_direct_messages?:bool,
     *     can_restrict_members?:bool,
     *     can_promote_members?:bool,
     *     can_post_messages?:bool,
     *     can_edit_messages?:bool,
     *     can_delete_messages?:bool,
     *     can_pin_messages?:bool,
     *     can_post_stories?:bool,
     *     can_edit_stories?:bool,
     *     can_delete_stories?:bool,
     *     can_send_messages?:bool,
     *     can_send_media_messages?:bool,
     *     can_send_audios?:bool,
     *     can_send_documents?:bool,
     *     can_send_photos?:bool,
     *     can_send_videos?:bool,
     *     can_send_video_notes?:bool,
     *     can_send_voice_notes?:bool,
     *     can_send_polls?:bool,
     *     can_send_other_messages?:bool,
     *     can_add_web_page_previews?:bool
     *  } $data
     */
    public static function privilegesFromArray(ChatMember $member, array $data): ChatMember
    {
        $member->canBeEdited = $data['can_be_edited'] ?? false;
        $member->canChangeInfo = $data['can_change_info'] ?? false;
        $member->canInviteUsers = $data['can_invite_users'] ?? false;
        $member->canManageChat = $data['can_manage_chat'] ?? false;
        $member->canManageTopics = $data['can_manage_topics'] ?? false;
        $member->canManageVideoChats = $data['can_manage_video_chats'] ?? false;
        $member->canManageVoiceChats = $data['can_manage_voice_chats'] ?? false;
        $member->canManageDirectMessages = $data['can_manage_direct_messages'] ?? false;
        $member->canRestrictMembers = $data['can_restrict_members'] ?? false;
        $member->canPromoteMembers = $data['can_promote_members'] ?? false;
        $member->canPostMessages = $data['can_post_messages'] ?? false;
        $member->canEditMessages = $data['can_edit_messages'] ?? false;
        $member->canDeleteMessages = $data['can_delete_messages'] ?? false;
        $member->canPinMessages = $data['can_pin_messages'] ?? false;
        $member->canPostStories = $data['can_post_stories'] ?? false;
        $member->canEditStories = $data['can_edit_stories'] ?? false;
        $member->canDeleteStories = $data['can_delete_stories'] ?? false;
        $member->canSendMessages = $data['can_send_messages'] ?? false;
        $member->canSendMediaMessages = $data['can_send_media_messages'] ?? false;
        $member->canSendAudios = $data['can_send_audios'] ?? false;
        $member->canSendDocuments = $data['can_send_documents'] ?? false;
        $member->canSendPhotos = $data['can_send_photos'] ?? false;
        $member->canSendVideos = $data['can_send_videos'] ?? false;
        $member->canSendVideoNotes = $data['can_send_video_notes'] ?? false;
        $member->canSendVoiceNotes = $data['can_send_voice_notes'] ?? false;
        $member->canSendPolls = $data['can_send_polls'] ?? false;
        $member->canSendOtherMessages = $data['can_send_other_messages'] ?? false;
        $member->canAddWebPagePreviews = $data['can_add_web_page_previews'] ?? false;

        return $member;
    }

    public function canBeEdited(): bool
    {
        return $this->canBeEdited;
    }

    public function canChangeInfo(): bool
    {
        return $this->canChangeInfo;
    }

    public function canInviteUsers(): bool
    {
        return $this->canInviteUsers;
    }

    public function canManageChat(): bool
    {
        return $this->canManageChat;
    }

    public function canManageTopics(): bool
    {
        return $this->canManageTopics;
    }

    public function canManageVideoChats(): bool
    {
        return $this->canManageVideoChats;
    }

    public function canManageVoiceChats(): bool
    {
        return $this->canManageVoiceChats;
    }

    public function canManageDirectMessages(): bool
    {
        return $this->canManageDirectMessages;
    }

    public function canRestrictMembers(): bool
    {
        return $this->canRestrictMembers;
    }

    public function canPromoteMembers(): bool
    {
        return $this->canPromoteMembers;
    }

    public function canPostMessages(): bool
    {
        return $this->canPostMessages;
    }

    public function canEditMessages(): bool
    {
        return $this->canEditMessages;
    }

    public function canDeleteMessages(): bool
    {
        return $this->canDeleteMessages;
    }

    public function canPinMessages(): bool
    {
        return $this->canPinMessages;
    }

    public function canPostStories(): bool
    {
        return $this->canPostStories;
    }

    public function canEditStories(): bool
    {
        return $this->canEditStories;
    }

    public function canDeleteStories(): bool
    {
        return $this->canDeleteStories;
    }

    public function canSendMessages(): bool
    {
        return $this->canSendMessages;
    }

    public function canSendMediaMessages(): bool
    {
        return $this->canSendMediaMessages;
    }

    public function canSendAudios(): bool
    {
        return $this->canSendAudios;
    }

    public function canSendDocuments(): bool
    {
        return $this->canSendDocuments;
    }

    public function canSendPhotos(): bool
    {
        return $this->canSendPhotos;
    }

    public function canSendVideos(): bool
    {
        return $this->canSendVideos;
    }

    public function canSendVideoNotes(): bool
    {
        return $this->canSendVideoNotes;
    }

    public function canSendVoiceNotes(): bool
    {
        return $this->canSendVoiceNotes;
    }

    public function canSendPolls(): bool
    {
        return $this->canSendPolls;
    }

    public function canSendOtherMessages(): bool
    {
        return $this->canSendOtherMessages;
    }

    public function canAddWebPagePreviews(): bool
    {
        return $this->canAddWebPagePreviews;
    }

    /**
     * @return array<string, mixed>
     */
    public function privilegesToArray(): array
    {
        return [
            'can_be_edited' => $this->canBeEdited,
            'can_change_info' => $this->canChangeInfo,
            'can_invite_users' => $this->canInviteUsers,
            'can_manage_chat' => $this->canManageChat,
            'can_manage_topics' => $this->canManageTopics,
            'can_manage_video_chats' => $this->canManageVideoChats,
            'can_manage_voice_chats' => $this->canManageVoiceChats,
            'can_manage_direct_messages' => $this->canManageDirectMessages,
            'can_restrict_members' => $this->canRestrictMembers,
            'can_promote_members' => $this->canPromoteMembers,
            'can_post_messages' => $this->canPostMessages,
            'can_edit_messages' => $this->canEditMessages,
            'can_delete_messages' => $this->canDeleteMessages,
            'can_pin_messages' => $this->canPinMessages,
            'can_post_stories' => $this->canPostStories,
            'can_edit_stories' => $this->canEditStories,
            'can_delete_stories' => $this->canDeleteStories,
            'can_send_messages' => $this->canSendMessages,
            'can_send_media_messages' => $this->canSendMediaMessages,
            'can_send_audios' => $this->canSendAudios,
            'can_send_documents' => $this->canSendDocuments,
            'can_send_photos' => $this->canSendPhotos,
            'can_send_videos' => $this->canSendVideos,
            'can_send_video_notes' => $this->canSendVideoNotes,
            'can_send_voice_notes' => $this->canSendVoiceNotes,
            'can_send_polls' => $this->canSendPolls,
            'can_send_other_messages' => $this->canSendOtherMessages,
            'can_add_web_page_previews' => $this->canAddWebPagePreviews,
        ];
    }
}
