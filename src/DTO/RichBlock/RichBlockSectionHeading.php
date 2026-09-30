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
class RichBlockSectionHeading implements RichBlockItem, Arrayable
{
    private const TYPE = 'heading';
    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $text;
    private int $size;

    /**
     * @param  array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     size: int
     * }  $data
     *
     * @return RichBlockSectionHeading
     */
    public static function fromArray(array $data): RichBlockSectionHeading
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockSectionHeading = new self();

        $richBlockSectionHeading->text = app(RichTextFactory::class)->fromData($data['text']);

        $richBlockSectionHeading->size = $data['size'];

        return $richBlockSectionHeading;
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

    public function size(): int
    {
        return $this->size;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'size' => $this->size,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
