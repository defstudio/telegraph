<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class PollAnswer implements Arrayable
{
    private string $pollId;
    private ?Chat $voterChat = null;
    private ?User $user = null;

    /** @var array<mixed, int> */
    private array $optionIds;


    private function __construct()
    {
    }

    /**
     * @param array{poll_id:string, voter_chat:array<object>, user:array<object>, option_ids:array<mixed, int>} $data
     */
    public static function fromArray(array $data): PollAnswer
    {
        $pollAnswer = new self();

        $pollAnswer->pollId = $data['poll_id'];

        if (isset($data['user'])) {
            $pollAnswer->user = User::fromArray($data['user']);
        }

        if (isset($data['voter_chat'])) {
            $pollAnswer->voterChat = Chat::fromArray($data['voter_chat']);
        }

        $pollAnswer->optionIds = $data['option_ids'];


        return $pollAnswer;
    }

    public function pollId(): string
    {
        return $this->pollId;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function voterChat(): ?Chat
    {
        return $this->voterChat;
    }

    public function optionIds(): array
    {
        return $this->optionIds;
    }

    public function toArray(): array
    {
        return array_filter([
            'poll_id' => $this->pollId,
            'user' => $this->user?->toArray(),
            'voter_chat' => $this->voterChat?->toArray(),
            'option_ids' => $this->optionIds,

        ], fn($value) => $value !== null);
    }
}
