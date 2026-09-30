<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextUrl implements RichTextItem
{
    private const TYPE = 'url';
    /** @var RichTextItem|Collection<int|string,RichTextItem> */
    private RichTextItem|Collection $text;
    private string $url;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     url: string
     * }  $data
     *
     * @return RichTextUrl
     */
    public static function fromData(string|array $data): RichTextUrl
    {
        $richTextUrl = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextUrl->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextUrl->url = $data['url'];

        return $richTextUrl;
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

    public function url(): string
    {
        return $this->url;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'url' => $this->url,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
