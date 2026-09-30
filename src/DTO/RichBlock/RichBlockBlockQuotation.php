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
class RichBlockBlockQuotation implements RichBlockItem, Arrayable
{
    private const TYPE = 'blockquote';
    /** @var Collection<array-key,RichBlockItem> */
    private Collection $blocks;
    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $credit;

    public function __construct()
    {
        $this->blocks = Collection::empty();
        $this->credit = Collection::empty();
    }

    /**
     * @param  array{
     *     type: string,
     *     blocks: array<array-key,Object>,
     *     credit?: string|array<string ,mixed>,
     * }  $data
     *
     * @return RichBlockBlockQuotation
     */
    public static function fromArray(array $data): RichBlockBlockQuotation
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockBlockQuotation = new self();

        /* @phpstan-ignore-next-line */
        $richBlockBlockQuotation->blocks = collect($data['blocks'])->map(fn (array $blockData) => app(RichBlockFactory::class)->fromArray($blockData));

        if (isset($data['credit']) && $data['credit']) {
            $richBlockBlockQuotation->credit = app(RichTextFactory::class)->fromData($data['credit']);
        }

        return $richBlockBlockQuotation;
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
    public function credit(): RichTextItem|Collection
    {
        return $this->credit;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'blocks' => $this->blocks->toArray(),
            'credit' => $this->credit instanceof RichTextItem
                ? $this->credit->build()
                : $this->credit->map(fn (RichTextItem $item) => $item->build())->toArray(),
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
