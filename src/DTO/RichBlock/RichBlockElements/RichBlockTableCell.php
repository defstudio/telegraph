<?php

namespace DefStudio\Telegraph\DTO\RichBlock\RichBlockElements;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockTableCell implements Arrayable
{
    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $text;
    private bool $isHeader = false;
    private ?int $colspan = null;
    private ?int $rowspan = null;
    private string $align;
    private string $valign;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  array{
     *     text?: string|array<string ,mixed>,
     *     is_header?: bool,
     *     colspan?: int,
     *     rowspan?: int,
     *     align: string,
     *     valign:string
     * }  $data
     */
    public static function fromArray(array $data): RichBlockTableCell
    {
        $richBlockTableCell = new self();

        if (isset($data['text'])) {
            $richBlockTableCell->text = app(RichTextFactory::class)->fromData($data['text']);
        }

        $richBlockTableCell->isHeader = $data['is_header'] ?? false;
        $richBlockTableCell->colspan = $data['colspan'] ?? null;
        $richBlockTableCell->rowspan = $data['rowspan'] ?? null;
        $richBlockTableCell->align = $data['align'];
        $richBlockTableCell->valign = $data['valign'];

        return $richBlockTableCell;
    }

    /**
     * @return RichTextItem|Collection<int|string,RichTextItem>
     */
    public function text(): RichTextItem|Collection
    {
        return $this->text;
    }

    public function isHeader(): bool
    {
        return $this->isHeader;
    }

    public function colspan(): ?int
    {
        return $this->colspan;
    }

    public function rowspan(): ?int
    {
        return $this->rowspan;
    }

    public function align(): string
    {
        return $this->align;
    }

    public function valign(): string
    {
        return $this->valign;
    }

    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : ($this->text->isEmpty()
                    ? null
                    : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray()),
            'is_header' => $this->isHeader,
            'colspan' => $this->colspan,
            'rowspan' => $this->rowspan,
            'align' => $this->align,
            'valign' => $this->valign,
        ], fn ($value) => $value !== null);
    }
}
