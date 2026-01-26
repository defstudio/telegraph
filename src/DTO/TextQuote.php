<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class TextQuote implements Arrayable
{
    private string $text;
    private Collection $entities;
    private int $position;
    private bool $isManual = false;

    private function __construct()
    {
        $this->entities = Collection::empty();

    }

    /**
     * @param  array{
     *     text: string,
     *     entities?: array<object>,
     *     position: int,
     *     is_manual?: bool,
     * }  $data
     */
    public static function fromArray(array $data): TextQuote
    {
        $textQuote = new self();

        $textQuote->text = $data['text'] ?? '';
        $textQuote->position = $data['position'] ?? '';
        $textQuote->isManual = $data['is_manual'] ?? false;

        if (isset($data['entities']) && $data['entities']) {
            /* @phpstan-ignore-next-line */
            $textQuote->entities = collect($data['entities'])->map(fn (array $entity) => Entity::fromArray($entity));
        }

        return $textQuote;
    }

    public function text(): string
    {
        return $this->text;
    }

    /**
     * @return Collection<array-key, Entity>
     */
    public function entities(): Collection
    {
        return $this->entities;
    }

    public function position(): int
    {
        return $this->position;
    }

    public function isManual(): bool
    {
        return $this->isManual;
    }

    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text,
            'entities' => $this->entities->toArray(),
            'position' => $this->position,
            'is_manual' => $this->isManual,
        ], fn ($value) => $value !== null);
    }
}
