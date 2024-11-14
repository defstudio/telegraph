<?php

namespace DefStudio\Telegraph\DTO;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|bool|array<string, mixed>>
 */
class ChatJoinRequest implements Arrayable
{
    private int $userChatId;
    private ?CarbonInterface $date = null;
    private ?string $bio = null;
    private ?ChatInviteLink $inviteLink = null;
    private Chat $chat;
    private User $from;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     user_chat_id: int,
     *     date: int,
     *     bio?: string,
     *     invite_link?: array<string, mixed>,
     *     chat: array<string, mixed>,
     *     from: array<string, mixed>,
     * } $data
     */
    public static function fromArray(array $data): ChatJoinRequest
    {
        $request = new self();

        $request->userChatId = $data['user_chat_id'];

        if (isset($data['date'])) {
            /* @phpstan-ignore-next-line */
            $request->date = Carbon::createFromTimestamp($data['date']);
        }

        if (isset($data['bio'])) {
            $request->bio = $data['bio'];
        }

        if (isset($data['invite_link'])) {
            /* @phpstan-ignore-next-line */
            $request->inviteLink = ChatInviteLink::fromArray($data['invite_link']);
        }

        $request->chat = Chat::fromArray($data['chat']);

        $request->from = User::fromArray($data['from']);

        return $request;
    }

    public function userChatId(): int
    {
        return $this->userChatId;
    }

    public function date(): ?CarbonInterface
    {
        return $this->date;
    }

    public function bio(): ?string
    {
        return $this->bio;
    }

    public function inviteLink(): ?ChatInviteLink
    {
        return $this->inviteLink;
    }

    public function chat(): Chat
    {
        return $this->chat;
    }

    public function from(): User
    {
        return $this->from;
    }

    public function toArray(): array
    {
        return array_filter([
            'user_chat_id' => $this->userChatId,
            'date' => $this->date?->timestamp,
            'bio' => $this->bio,
            'invite_link' => $this->inviteLink?->toArray(),
            'chat' => $this->chat->toArray(),
            'from' => $this->from->toArray(),
        ], fn ($value) => $value !== null);
    }
}
