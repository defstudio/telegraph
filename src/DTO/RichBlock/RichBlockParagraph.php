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
class RichBlockParagraph implements RichBlockItem, Arrayable
{
    private const TYPE = 'paragraph';

    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $text;

    /**
     * @param  array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     * }  $data
     *
     * @return RichBlockParagraph
     */
    public static function fromArray(array $data): RichBlockParagraph
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockParagraph = new self();

        $richBlockParagraph->text = app(RichTextFactory::class)->fromData($data['text']);

        return $richBlockParagraph;
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

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
