<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextReferenceLink implements RichTextItem
{
    private const TYPE = 'reference_link';
    /** @var RichTextItem|Collection<int|string,RichTextItem> */
    private RichTextItem|Collection $text;
    private string $referenceName;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     reference_name: string
     * }  $data
     *
     * @return RichTextReferenceLink
     */
    public static function fromData(string|array $data): RichTextReferenceLink
    {
        $richTextReferenceLink = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextReferenceLink->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextReferenceLink->referenceName = $data['reference_name'];

        return $richTextReferenceLink;
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

    public function referenceName(): string
    {
        return $this->referenceName;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'reference_name' => $this->referenceName,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
