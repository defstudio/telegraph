<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class Poll implements Arrayable
{
    private string $id;
    private string $question;
    /** @var Collection<int, Entity>|null */
    private ?Collection $questionEntities = null;
    /** @var Collection<int, PollOption> */
    private Collection $options;
    private int $totalVoterCount;
    private bool $isClosed;
    private bool $isAnonymous;
    private string $type;
    private bool $allowsMultipleAnswers;

    private function __construct()
    {
    }

    /**
     * @param array{id:string, question:string,question_entities?: array<object> , options:array<object>, total_voter_count:int, is_closed:bool, is_anonymous:bool, type:string, allows_multiple_answers:bool} $data
     */
    public static function fromArray(array $data): Poll
    {
        $poll = new self();

        $poll->id = $data['id'];
        $poll->question = $data['question'];

        if (!empty($data['question_entities'])) {
            /* @phpstan-ignore-next-line */
            $poll->questionEntities = collect($data['question_entities'])->map(fn(array $entity) => Entity::fromArray($entity));
        }

        /* @phpstan-ignore-next-line */
        $poll->options = collect($data['options'])->map(fn(array $option) => PollOption::fromArray($option));

        $poll->totalVoterCount = $data['total_voter_count'];
        $poll->isClosed = $data['is_closed'];
        $poll->isAnonymous = $data['is_anonymous'];
        $poll->type = $data['type'];
        $poll->allowsMultipleAnswers = $data['allows_multiple_answers'];

        return $poll;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function question(): string
    {
        return $this->question;
    }

    /**
     * @return Collection<int,Entity>|null
     */
    public function questionEntities(): ?Collection
    {
        return $this->questionEntities;
    }

    /**
     * @return Collection<int, PollOption>
     */
    public function options(): Collection
    {
        return $this->options;
    }

    public function totalVoterCount(): int
    {
        return $this->totalVoterCount;
    }

    public function isClosed(): bool
    {
        return $this->isClosed;
    }

    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function allowsMultipleAnswers(): bool
    {
        return $this->allowsMultipleAnswers;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'question' => $this->question,
            'question_entities' => $this->questionEntities?->toArray(),
            'options' => $this->options->toArray(),
            'total_voter_count' => $this->totalVoterCount,
            'is_closed' => $this->isClosed,
            'is_anonymous' => $this->isAnonymous,
            'type' => $this->type,
            'allows_multiple_answers' => $this->allowsMultipleAnswers,

        ], fn($value) => $value !== null);
    }
}
