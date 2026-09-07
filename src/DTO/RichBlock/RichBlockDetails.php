<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichBlockFactory;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockDetails implements RichBlockItem, Arrayable
{
    private const TYPE = 'details';
    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $summary;
    /** @var Collection<array-key,RichBlockItem> */
    private Collection $blocks;
    private bool $isOpen = false;

    public function __construct()
    {
        $this->blocks = Collection::empty();
        $this->summary = Collection::empty();
    }

    /**
     * @param  array{
     *     type: string,
     *     summary: string|array<string ,mixed>,
     *     blocks: array<array-key,Object>,
     *     is_open?: bool,
     * }  $data
     *
     * @return RichBlockDetails
     */
    public static function fromArray(array $data): RichBlockDetails
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockDetails = new self();

        $richBlockDetails->summary = app(RichTextFactory::class)->fromData($data['summary']);

        /* @phpstan-ignore-next-line */
        $richBlockDetails->blocks = collect($data['blocks'] ?? [])->map(fn (array $blockData) => app(RichBlockFactory::class)->fromArray($blockData));

        $richBlockDetails->isOpen = $data['is_open'] ?? false;

        return $richBlockDetails;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    /**
     * @return Collection<array-key, RichBlockItem>
     */
    public function blocks(): Collection
    {
        return $this->blocks;
    }

    /**
     * @return RichTextItem|Collection<int|string,RichTextItem>
     */
    public function summary(): RichTextItem|Collection
    {
        return $this->summary;
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'summary' => $this->summary instanceof RichTextItem
                ? $this->summary->build()
                : $this->summary->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'blocks' => $this->blocks->toArray(),
            'is_open' => $this->isOpen ? true : null,
        ], fn ($value) => $value !== null);
    }
}
