<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockTableCell;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockTable implements RichBlockItem, Arrayable
{
    private const TYPE = 'table';
    /** @var Collection<int|string, Collection<int|string, RichBlockTableCell>> */
    private Collection $cells;
    private bool $isBordered = false;
    private bool $isStriped = false;
    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $caption;

    public function __construct()
    {
        $this->caption = Collection::empty();
        $this->cells = Collection::empty();
    }

    /**
     * @param  array{
     *     type: string,
     *     cells: array<array-key, array<array-key, array<string,mixed>>>,
     *     is_bordered?: bool,
     *     is_striped?: bool,
     *     caption?: string|array<string ,mixed>,
     * }  $data
     *
     * @return RichBlockTable
     */
    public static function fromArray(array $data): RichBlockTable
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockTable = new self();

        $richBlockTable->cells = collect($data['cells'])
            ->map(
                fn (array $row, $rowIndex) => collect($row)
                ->map(
                    fn (array $cell) => RichBlockTableCell::fromArray($cell)
                )
            );

        $richBlockTable->isBordered = $data['is_bordered'] ?? false;
        $richBlockTable->isStriped = $data['is_striped'] ?? false;

        if (isset($data['caption']) && $data['caption']) {
            $richBlockTable->caption = app(RichTextFactory::class)->fromData($data['caption']);
        }

        return $richBlockTable;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    /**
     * @return Collection<int|string, Collection<int|string, RichBlockTableCell>>
     */
    public function cells(): Collection
    {
        return $this->cells;
    }

    public function isBordered(): bool
    {
        return $this->isBordered;
    }

    public function isStriped(): bool
    {
        return $this->isStriped;
    }

    /**
     * @return RichTextItem|Collection<int|string,RichTextItem>
     */
    public function caption(): RichTextItem|Collection
    {
        return $this->caption;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'cells' => $this->cells->map(fn (Collection $row) => $row->toArray())->toArray(),
            'is_bordered' => $this->isBordered ? true : null,
            'is_striped' => $this->isStriped ? true : null,
            'caption' => $this->caption instanceof RichTextItem
                ? $this->caption->build()
                : $this->caption->map(fn (RichTextItem $item) => $item->build())->toArray(),
        ], fn ($value) => $value !== null);
    }
}
