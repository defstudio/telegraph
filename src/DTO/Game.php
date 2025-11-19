<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\Downloadable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class Game implements Arrayable
{
    private string $title;
    private string $description;
    private Collection $photos;
    private Collection $entities;
    private ?Animation $animation = null;
    private ?string $text = null;


    private function __construct()
    {
        $this->photos = Collection::empty();
        $this->entities = Collection::empty();

    }

    /**
     * @param  array{
     *     title: string,
     *     description: string,
     *     entities?: array<object>,
     *     photo?: array<string, mixed>,
     *     animation?:array<string, mixed>,
     *     text?:string
     * }  $data
     */
    public static function fromArray(array $data): Game
    {
        $game = new self();

        $game->title =  $data['title'] ?? '';
        $game->description =  $data['description'] ?? '';
        $game->text =  $data['text'] ?? '';

        $game->photos = collect($data['photo'] ?? [])->map(fn (array $photoData) => Photo::fromArray($photoData));

        if (isset($data['animation'])) {
            $game->animation = Animation::fromArray($data['animation']);
        }

        if (isset($data['entities']) && $data['entities']) {
            /* @phpstan-ignore-next-line */
            $game->entities = collect($data['entities'])->map(fn (array $entity) => Entity::fromArray($entity));
        }

        return $game;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function animation(): ?Animation
    {
        return $this->animation;
    }

    /**
     * @return Collection<array-key, Photo>
     */
    public function photos(): Collection
    {
        return $this->photos;
    }

    /**
     * @return Collection<array-key, Entity>
     */
    public function entities(): Collection
    {
        return $this->entities;
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'text' => $this->text,
            'photos' => $this->photos->toArray(),
            'animation' => $this->animation?->toArray(),
            'entities' => $this->entities->toArray(),
        ], fn($value) => $value !== null);
    }
}
