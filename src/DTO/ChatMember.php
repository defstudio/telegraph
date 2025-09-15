<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Concerns\InteractWithPrivileges;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|bool|array<string, mixed>>
 */
class ChatMember implements Arrayable
{
    use InteractWithPrivileges;

    public const STATUS_CREATOR = 'creator';
    public const STATUS_ADMINISTRATOR = 'administrator';
    public const STATUS_MEMBER = 'member';
    public const STATUS_RESTRICTED = 'restricted';
    public const STATUS_LEFT = 'left';
    public const STATUS_KICKED = 'kicked';

    private string $status;
    private User $user;
    private bool $isAnonymous = false;
    private bool $isMember = false;
    private ?string $customTitle = null;
    private ?int $untilDate = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     status:string,
     *     user:array<string, mixed>,
     *     is_anonymous?:bool,
     *     is_member?:bool,
     *     custom_title?:string,
     *     until_date?:int,
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
    public static function fromArray(array $data): ChatMember
    {
        $member = new self();

        $member->status = $data['status'];
        $member->user = User::fromArray($data['user']);

        $member->isAnonymous = $data['is_anonymous'] ?? false;
        $member->isMember = $data['is_member'] ?? false;
        $member->customTitle = $data['custom_title'] ?? null;
        $member->untilDate = $data['until_date'] ?? null;

        $member = self::privilegesFromArray($member, $data);

        return $member;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function isMember(): bool
    {
        return $this->isMember;
    }

    public function customTitle(): ?string
    {
        return $this->customTitle;
    }

    public function untilDate(): ?int
    {
        return $this->untilDate;
    }

    public function is_member(): bool
    {
        return $this->isMember();
    }

    public function custom_title(): string
    {
        return $this->customTitle() ?? '';
    }

    public function until_date(): ?int
    {
        return $this->untilDate();
    }

    public function toArray(): array
    {
        return array_filter([
            'status' => $this->status,
            'user' => $this->user->toArray(),
            'is_anonymous' => $this->isAnonymous,
            'is_member' => $this->isMember,
            'custom_title' => $this->customTitle,
            'until_date' => $this->untilDate,
            ...$this->privilegesToArray(),
        ], fn ($value) => $value !== null);
    }
}
