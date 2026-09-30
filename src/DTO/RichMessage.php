<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\Factories\RichBlockFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class RichMessage implements Arrayable
{
    /** @var Collection<array-key, RichBlockItem> */
    private Collection $blocks;

    private bool $isRtl = false;

    private function __construct()
    {
        $this->blocks = Collection::empty();
    }

    /**
     * @param  array{
     *     blocks: array<Object>,
     *     is_rtl?: bool
     * }  $data
     *
     * @return RichMessage
     */
    public static function fromArray(array $data): RichMessage
    {
        $richMessage = new self();

        /* @phpstan-ignore-next-line */
        $richMessage->blocks = collect($data['blocks'])->map(fn (array $blockData) => app(RichBlockFactory::class)->fromArray($blockData));

        $richMessage->isRtl = $data['is_rtl'] ?? false;

        return $richMessage;
    }

    /**
     * @return Collection<array-key, RichBlockItem>
     */
    public function blocks(): Collection
    {
        return $this->blocks;
    }

    public function isRtl(): bool
    {
        return $this->isRtl;
    }

    public function toArray(): array
    {
        return array_filter([
            'blocks' => $this->blocks->toArray(),
            'is_rtl' => $this->isRtl,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
