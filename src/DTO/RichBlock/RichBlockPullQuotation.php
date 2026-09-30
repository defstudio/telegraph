<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockPullQuotation implements RichBlockItem, Arrayable
{
    private const TYPE = 'pullquote';
    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $text;
    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $credit;

    public function __construct()
    {
        $this->text = Collection::empty();
        $this->credit = Collection::empty();
    }

    /**
     * @param  array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     credit?: string|array<string ,mixed>,
     * }  $data
     *
     * @return RichBlockPullQuotation
     */
    public static function fromArray(array $data): RichBlockPullQuotation
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockPullQuotation = new self();

        $richBlockPullQuotation->text = app(RichTextFactory::class)->fromData($data['text']);

        if (isset($data['credit']) && $data['credit']) {
            $richBlockPullQuotation->credit = app(RichTextFactory::class)->fromData($data['credit']);
        }

        return $richBlockPullQuotation;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    /**
     * @return RichTextItem|Collection<int|string,RichTextItem>
     */
    public function text(): RichTextItem|Collection
    {
        return $this->text;
    }

    /**
     * @return RichTextItem|Collection<int|string,RichTextItem>
     */
    public function credit(): RichTextItem|Collection
    {
        return $this->credit();
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'credit' => $this->credit instanceof RichTextItem
                ? $this->credit->build()
                : $this->credit->map(fn (RichTextItem $item) => $item->build())->toArray(),
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
