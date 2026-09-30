<?php

namespace DefStudio\Telegraph\DTO\RichBlock\RichBlockElements;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockCaption implements Arrayable
{
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
     *     text: string|array<string ,mixed>,
     *     credit?: string|array<string ,mixed>,
     * }  $data
     */
    public static function fromArray(array $data): RichBlockCaption
    {
        $richBlockCaption = new self();

        $richBlockCaption->text = app(RichTextFactory::class)->fromData($data['text']);

        if (isset($data['credit']) && $data['credit']) {
            $richBlockCaption->credit = app(RichTextFactory::class)->fromData($data['credit']);
        }

        return $richBlockCaption;
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
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'credit' => $this->credit instanceof RichTextItem
                ? $this->credit->build()
                : $this->credit->map(fn (RichTextItem $item) => $item->build())->toArray(),
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
