<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextAnchorLink implements RichTextItem
{
    private const TYPE = 'anchor_link';
    /** @var RichTextItem|Collection<int|string,RichTextItem> */
    private RichTextItem|Collection $text;
    private string $anchorName;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     anchor_name: string
     * }  $data
     *
     * @return RichTextAnchorLink
     */
    public static function fromData(string|array $data): RichTextAnchorLink
    {
        $richTextAnchorLink = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextAnchorLink->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextAnchorLink->anchorName = $data['anchor_name'];

        return $richTextAnchorLink;
    }

    public function type(): ?string
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

    public function anchorName(): string
    {
        return $this->anchorName;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'anchor_name' => $this->anchorName,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
