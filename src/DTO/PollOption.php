<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class PollOption implements Arrayable
{
    private string $text;
    /** @var Collection<int, Entity>|null  */
    private ?Collection $textEntities = null;

    private int $voterCount;

    private function __construct()
    {
    }

    /**
     * @param array{text:string, text_entities:array<object>, voter_count:int} $data
     */
    public static function fromArray(array $data): PollOption
    {
        $pollOption = new self();

        $pollOption->text = $data['text'];

        if (!empty($data['text_entities'])) {
            /* @phpstan-ignore-next-line */
            $pollOption->textEntities = collect($data['text_entities'])->map(fn (array $entity) => Entity::fromArray($entity));
        }

        $pollOption->voterCount = $data['voter_count'];

        return $pollOption;
    }

    public function text(): string
    {
        return $this->text;
    }

    /**
     * @return Collection<int, Entity>|null
     */
    public function textEntities(): ?Collection
    {
        return $this->textEntities;
    }

    public function voterCount(): int
    {
        return $this->voterCount;
    }

    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text,
            'text_entities' => $this->textEntities,
            'voter_count' => $this->voterCount,

        ], fn ($value) => $value !== null);
    }
}
