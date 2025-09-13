<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class ChatMemberUpdate implements Arrayable
{
    private CarbonInterface $date;

    private Chat $chat;
    private User $from;
    private ChatMember $previous;
    private ChatMember $new;
    private ?ChatInviteLink $inviteLink;
    private ?bool $viaJoinRequest;
    private ?bool $viaChatFolderInviteLink;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     date:int,
     *     chat:array<string, mixed>,
     *     from:array<string, mixed>,
     *     old_chat_member:array<string, mixed>,
     *     new_chat_member:array<string, mixed>,
     *     invite_link?:array<string, mixed>,
     *     via_join_request?:bool,
     *     via_chat_folder_invite_link?:bool
     *  } $data
     */
    public static function fromArray(array $data): ChatMemberUpdate
    {
        $chatMemberUpdate = new self();

        $chatMemberUpdate->date = Carbon::createFromTimestamp($data['date']);
        $chatMemberUpdate->chat = Chat::fromArray($data['chat']);
        $chatMemberUpdate->from = User::fromArray($data['from']);
        $chatMemberUpdate->previous = ChatMember::fromArray($data['old_chat_member']);
        $chatMemberUpdate->new = ChatMember::fromArray($data['new_chat_member']);

        if (isset($data['invite_link'])) {
            $chatMemberUpdate->inviteLink = ChatInviteLink::fromArray($data['invite_link']);
        }

        $chatMemberUpdate->viaJoinRequest = $data['via_join_request'] ?? null;
        $chatMemberUpdate->viaChatFolderInviteLink = $data['via_chat_folder_invite_link'] ?? null;

        return $chatMemberUpdate;
    }

    public function date(): CarbonInterface
    {
        return $this->date;
    }

    public function chat(): Chat
    {
        return $this->chat;
    }

    public function from(): User
    {
        return $this->from;
    }

    public function previous(): ChatMember
    {
        return $this->previous;
    }

    public function new(): ChatMember
    {
        return $this->new;
    }

    public function inviteLink(): ?ChatInviteLink
    {
        return $this->inviteLink;
    }

    public function viaJoinRequest(): ?bool
    {
        return $this->viaJoinRequest;
    }

    public function viaChatFolderInviteLink(): ?bool
    {
        return $this->viaChatFolderInviteLink;
    }

    public function toArray(): array
    {
        return array_filter([
            'chat' => $this->chat->toArray(),
            'from' => $this->from->toArray(),
            'date' => $this->date->toISOString(),
            'previous' => $this->previous->toArray(),
            'new' => $this->new->toArray(),
            'invite_link' => $this->inviteLink?->toArray(),
            'via_join_request' => $this->viaJoinRequest,
            'via_chat_folder_invite_link' => $this->viaChatFolderInviteLink,
        ], fn($value) => $value !== null);
    }
}
