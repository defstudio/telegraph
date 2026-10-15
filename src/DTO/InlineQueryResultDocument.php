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

    protected ?string $caption = null;

    protected ?string $description = null;

    protected ?string $thumbUrl = null;

    protected ?int $thumbWidth = null;

    protected ?int $thumbHeight = null;

    protected ?string $parseMode = null;

    public static function make(string $id, string $title, string $url, string $mimeType): InlineQueryResultDocument
    {
        $result = new InlineQueryResultDocument;
        $result->id = $id;
        $result->title = $title;
        $result->url = $url;
        $result->mimeType = $mimeType;

        return $result;
    }

    public function caption(?string $caption): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function description(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function thumbUrl(?string $thumbUrl): static
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    public function thumbWidth(?int $thumbWidth): static
    {
        $this->thumbWidth = $thumbWidth;

        return $this;
    }

    public function thumbHeight(?int $thumbHeight): static
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
     * {@inheritDoc}
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
            'thumbnail_url' => $this->thumbUrl,
            'thumbnail_width' => $this->thumbWidth,
            'thumbnail_height' => $this->thumbHeight,
        ], fn ($value) => $value !== null);
    }
}
