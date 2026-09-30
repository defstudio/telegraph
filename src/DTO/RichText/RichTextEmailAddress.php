<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextEmailAddress implements RichTextItem
{
    private const TYPE = 'email_address';
    /** @var RichTextItem|Collection<int|string,RichTextItem> */
    private RichTextItem|Collection $text;
    private string $emailAddress;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     email_address: string
     * }  $data
     *
     * @return RichTextEmailAddress
     */
    public static function fromData(string|array $data): RichTextEmailAddress
    {
        $richTextEmailAddress = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextEmailAddress->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextEmailAddress->emailAddress = $data['email_address'];

        return $richTextEmailAddress;
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

    public function emailAddress(): string
    {
        return $this->emailAddress;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'email_address' => $this->emailAddress,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
