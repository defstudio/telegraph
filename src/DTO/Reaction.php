<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\DTO;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

/**
 * @implements Arrayable<string, string|int|bool|array<string, mixed>>
 */
class Reaction implements Arrayable
{
    private int $id;

    private Chat $chat;
    private ?Chat $actorChat = null;

    private ?User $from = null;

    /**
     * @var array<array<string, string>>
     */
    private array $oldReaction = [];

    /**
     * @var array<array<string, string>>
     */
    private array $newReaction = [];

    private CarbonInterface $date;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     message_id: int,
     *     chat: array<string, mixed>,
     *     actor_chat?: array<string, mixed>,
     *     user?: array<string, mixed>,
     *     date: int,
     *     old_reaction: array<int, array<string, string>>,
     *     new_reaction: array<int, array<string, string>>
     *  } $data
     */
    public static function fromArray(array $data): Reaction
    {
        $reaction = new self();

        $reaction->id = $data['message_id'];

        /* @phpstan-ignore-next-line */
        $reaction->chat = Chat::fromArray($data['chat']);

        if (isset($data['actor_chat'])) {
            /* @phpstan-ignore-next-line */
            $reaction->actorChat = Chat::fromArray($data['actor_chat']);
        }

        if (isset($data['user'])) {
            /* @phpstan-ignore-next-line */
            $reaction->from = User::fromArray($data['user']);
        }

        $reaction->date = Carbon::createFromTimestamp($data['date']);

        $reaction->oldReaction = $data['old_reaction'];
        $reaction->newReaction = $data['new_reaction'];

        return $reaction;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function chat(): Chat
    {
        return $this->chat;
    }

    public function actorChat(): ?Chat
    {
        return $this->actorChat;
    }

    public function from(): ?User
    {
        return $this->from;
    }

    /**
     * @return array<array<string, string>>
     */
    public function oldReaction(): array
    {
        return $this->oldReaction;
    }

    /**
     * @return array<array<string, string>>
     */
    public function newReaction(): array
    {
        return $this->newReaction;
    }

    public function date(): CarbonInterface
    {
        return $this->date;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'chat' => $this->chat->toArray(),
            'actor_chat' => $this->actorChat?->toArray(),
            'from' => $this->from?->toArray(),
            'old_reaction' => $this->oldReaction,
            'new_reaction' => $this->newReaction,
            'date' => $this->date->toISOString(),
        ], fn ($value) => $value !== null);
    }
}
