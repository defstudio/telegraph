<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\Exceptions\RichTextException;

class RichTextAnchor implements RichTextItem
{
    private const TYPE = 'anchor';

    private string $name;

    /**
     * @param  string|array{
     *     type: string,
     *     name: string,
     * }  $data
     *
     * @return RichTextAnchor
     */
    public static function fromData(string|array $data): RichTextAnchor
    {
        $richTextAnchor = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextAnchor->name = $data['name'];

        return $richTextAnchor;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'name' => $this->name,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}
