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
class RichBlockPreformatted implements RichBlockItem, Arrayable
{
    private const TYPE = 'pre';
    /** @var RichTextItem|Collection<array-key, RichTextItem> */
    private RichTextItem|Collection $text;
    private ?string $language = null;

    /**
     * @param  array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     language:string
     * }  $data
     *
     * @return RichBlockPreformatted
     */
    public static function fromArray(array $data): RichBlockPreformatted
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockPreformatted = new self();

        $richBlockPreformatted->text = app(RichTextFactory::class)->fromData($data['text']);

        $richBlockPreformatted->language = $data['language'];

        return $richBlockPreformatted;
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

    public function language(): ?string
    {
        return $this->language;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'language' => $this->language,
        ], fn ($value) => $value !== null);
    }
}
