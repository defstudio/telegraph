<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

class ChatMemberUpdate implements Arrayable
{
    private CarbonInterface $date;

    private Chat $chat;
    private User $from;
    private ChatMember $previous;
    private ChatMember $new;

    private function __construct()
    {
    }

    /**
     * @param array{date:int, chat:array<string, mixed>, from:array<string, mixed>, old_chat_member:array<string, mixed>, new_chat_member:array<string, mixed>} $data
     */
    public static function fromArray(array $data): ChatMemberUpdate
    {
        $chatMemberUpdate = new self();

        /* @phpstan-ignore-next-line  */
        $chatMemberUpdate->date = Carbon::createFromTimestamp($data['date']);
        /* @phpstan-ignore-next-line  */
        $chatMemberUpdate->chat = Chat::fromArray($data['chat']);
        /* @phpstan-ignore-next-line  */
        $chatMemberUpdate->from = User::fromArray($data['from']);
        /* @phpstan-ignore-next-line  */
        $chatMemberUpdate->previous = ChatMember::fromArray($data['old_chat_member']);
        /* @phpstan-ignore-next-line  */
        $chatMemberUpdate->new = ChatMember::fromArray($data['new_chat_member']);

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

    public function toArray(): array
    {
        return array_filter([
            'chat' => $this->chat->toArray(),
            'from' => $this->from->toArray(),
            'date' => $this->date->toISOString(),
            'previous' => $this->previous->toArray(),
            'new' => $this->new->toArray(),
        ], fn ($value) => $value !== null);
    }
}
