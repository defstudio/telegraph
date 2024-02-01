<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultContact extends InlineQueryResult
{
    protected string $type = 'contact';
    protected string $id;
    protected string $phoneNumber;
    protected string $firstName;
    protected string|null $message = null;
    protected int|null $thumbWidth = null;
    protected int|null $thumbHeight = null;
    protected string|null $lastName = null;
    protected string|null $vcard = null;
    protected string|null $thumbUrl = null;
    protected string|null $parseMode = null;

    public static function make(string $id, string $phoneNumber, string $firstName, string $message = null): InlineQueryResultContact
    {
        $result = new InlineQueryResultContact();
        $result->id = $id;
        $result->phoneNumber = $phoneNumber;
        $result->firstName = $firstName;
        $result->message = $message;

        return $result;
    }

    public function thumbWidth(int|null $thumbWidth): static
    {
        $this->thumbWidth = $thumbWidth;

        return $this;
    }

    public function thumbHeight(int|null $thumbHeight): static
    {
        $this->thumbHeight = $thumbHeight;

        return $this;
    }

    public function lastName(string|null $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function vcard(string|null $vcard): static
    {
        $this->vcard = $vcard;

        return $this;
    }

    public function thumbUrl(string|null $thumbUrl): static
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    public function html(): static
    {
        $this->parseMode = Telegraph::PARSE_HTML;

        return $this;
    }

    public function markdown(): static
    {
        $this->parseMode = Telegraph::PARSE_MARKDOWN;

        return $this;
    }

    public function markdownV2(): static
    {
        $this->parseMode = Telegraph::PARSE_MARKDOWNV2;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        $data = [
            'phone_number' => $this->phoneNumber,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'vcard' => $this->vcard,
            'thumb_url' => $this->thumbUrl,
            'thumb_width' => $this->thumbWidth,
            'thumb_height' => $this->thumbHeight,
        ];

        if($this->message !== null) {
            $data['input_message_content'] = [
                'message_text' => $this->message,
                'parse_mode' => $this->parseMode ?? config('telegraph.default_parse_mode', Telegraph::PARSE_HTML),
            ];
        }

        return array_filter($data, fn ($value) => $value !== null);
    }
}
