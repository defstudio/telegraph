<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextDateTime implements RichTextItem
{
    private const TYPE = 'date_time';
    /** @var RichTextItem|Collection<int|string,RichTextItem> */
    private RichTextItem|Collection $text;
    private int $unixTime;
    private string $dateTimeFormat;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     unix_time: int,
     *     date_time_format: string
     * }  $data
     *
     * @return RichTextDateTime
     */
    public static function fromData(string|array $data): RichTextDateTime
    {
        $richTextDateTime = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextDateTime->text = app(RichTextFactory::class)->fromData($data['text']);

        $richTextDateTime->unixTime = $data['unix_time'];

        $richTextDateTime->dateTimeFormat = $data['date_time_format'];

        return $richTextDateTime;
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

    public function unixTime(): int
    {
        return $this->unixTime;
    }

    public function dateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'unix_time' => $this->unixTime,
            'date_time_format' => $this->dateTimeFormat,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
