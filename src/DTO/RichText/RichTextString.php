<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\Exceptions\RichTextException;

class RichTextString implements RichTextItem
{
    private const TYPE = null;
    private string $text;

    /**
     * @param  string|array<string, mixed>  $data
     *
     * @return RichTextString
     * @throws RichTextException
     */
    public static function fromData(string|array $data): RichTextString
    {
        $richBlockAnchor = new self();

        if (!is_string($data)) {
            throw RichTextException::structureMismatch();
        }

        $richBlockAnchor->text = $data;

        return $richBlockAnchor;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function build(): array|string
    {
        return $this->text;
    }
}
