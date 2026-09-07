<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextBankCardNumber implements RichTextItem
{
    private const TYPE = 'bank_card_number';
    /** @var RichTextItem|Collection<int|string,RichTextItem> */
    private RichTextItem|Collection $text;
    private string $bankCardNumber;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array<string ,mixed>,
     *     bank_card_number: string
     * }  $data
     *
     * @return RichTextBankCardNumber
     */
    public static function fromData(string|array $data): RichTextBankCardNumber
    {
        $richTextBankCardNumber = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextBankCardNumber->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextBankCardNumber->bankCardNumber = $data['bank_card_number'];

        return $richTextBankCardNumber;
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

    public function bankCardNumber(): string
    {
        return $this->bankCardNumber;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'bank_card_number' => $this->bankCardNumber,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
