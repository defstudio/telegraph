<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextPhoneNumber implements RichTextItem
{
    private const TYPE = 'phone_number';
    /** @var RichTextItem|Collection<int|string,RichTextItem> */
    private RichTextItem|Collection $text;
    private string $phoneNumber;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     phone_number: string
     * }  $data
     *
     * @return RichTextPhoneNumber
     */
    public static function fromData(string|array $data): RichTextPhoneNumber
    {
        $richTextPhoneNumber = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextPhoneNumber->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextPhoneNumber->phoneNumber = $data['phone_number'];

        return $richTextPhoneNumber;
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

    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'phone_number' => $this->phoneNumber,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
