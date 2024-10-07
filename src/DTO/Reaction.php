<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\DTO;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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
     * @var Collection<array-key, ReactionType>
     */
    private Collection $oldReaction;

    /**
     * @var Collection<array-key, ReactionType>
     */
    private Collection $newReaction;

    private CarbonInterface $date;

    private function __construct()
    {
        $this->oldReaction = Collection::empty();
        $this->newReaction = Collection::empty();
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

        /* @phpstan-ignore-next-line */
        $reaction->oldReaction = collect($data['old_reaction'] ?? [])->map(fn (array $reactionData) => ReactionType::fromArray($reactionData));

        /* @phpstan-ignore-next-line */
        $reaction->newReaction = collect($data['new_reaction'] ?? [])->map(fn (array $reactionData) => ReactionType::fromArray($reactionData));

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
     * @return Collection<array-key, ReactionType>
     */
    public function oldReaction(): Collection
    {
        return $this->oldReaction;
    }

    /**
     * @return Collection<array-key, ReactionType>
     */
    public function newReaction(): Collection
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
            'old_reaction' => $this->oldReaction->toArray(),
            'new_reaction' => $this->newReaction->toArray(),
            'date' => $this->date->toISOString(),
        ], fn ($value) => $value !== null);
    }
}
