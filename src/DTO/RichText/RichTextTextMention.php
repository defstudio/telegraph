<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextTextMention implements RichTextItem
{
    private const TYPE = 'text_mention';
    /** @var RichTextItem|Collection<int|string,RichTextItem> */
    private RichTextItem|Collection $text;
    private User $user;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     user: array<string,mixed>
     * }  $data
     *
     * @return RichTextTextMention
     */
    public static function fromData(string|array $data): RichTextTextMention
    {
        $richTextTextMention = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextTextMention->text = app(RichTextFactory::class)->fromData($data['text']);

        $richTextTextMention->user = User::fromArray($data['user']);

        return $richTextTextMention;
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

    public function user(): User
    {
        return $this->user;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'user' => $this->user->toArray(),
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
