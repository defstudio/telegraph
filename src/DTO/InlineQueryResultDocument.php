<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultDocument extends InlineQueryResult
{
    protected string $type = 'document';
    protected string $id;
    protected string $title;
    protected string $url;
    protected string $mimeType;
    protected string|null $caption = null;
    protected string|null $description = null;
    protected string|null $thumbUrl = null;
    protected int|null $thumbWidth = null;
    protected int|null $thumbHeight = null;
    protected string|null $parseMode = null;

    public static function make(string $id, string $title, string $url, string $mimeType): InlineQueryResultDocument
    {
        $result = new InlineQueryResultDocument();
        $result->id = $id;
        $result->title = $title;
        $result->url = $url;
        $result->mimeType = $mimeType;

        return $result;
    }

    public function caption(string|null $caption): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function description(string|null $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function thumbUrl(string|null $thumbUrl): static
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
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
        return array_filter([
            'title' => $this->title,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode ?? config('telegraph.default_parse_mode', Telegraph::PARSE_HTML),
            'document_url' => $this->url,
            'mime_type' => $this->mimeType,
            'description' => $this->description,
            'thumb_url' => $this->thumbUrl,
            'thumb_width' => $this->thumbWidth,
            'thumb_height' => $this->thumbHeight,
        ], fn ($value) => $value !== null);
    }
}
