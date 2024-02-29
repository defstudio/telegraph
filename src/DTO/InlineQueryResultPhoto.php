<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultPhoto extends InlineQueryResult
{
    protected string $type = 'photo';
    protected string $id;
    protected string $url;
    protected string $thumbUrl;
    protected int|null $width = null;
    protected int|null $height = null;
    protected string|null $title = null;
    protected string|null $caption = null;
    protected string|null $description = null;
    protected string|null $parseMode = null;

    public static function make(string $id, string $url, string $thumbUrl): InlineQueryResultPhoto
    {
        $result = new InlineQueryResultPhoto();
        $result->id = $id;
        $result->url = $url;
        $result->thumbUrl = $thumbUrl;

        return $result;
    }

    public function width(int|null $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function height(int|null $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function title(string|null $title): static
    {
        $this->title = $title;

        return $this;
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
            'photo_url' => $this->url,
            'thumb_url' => $this->thumbUrl,
            'photo_width' => $this->width,
            'photo_height' => $this->height,
            'title' => $this->title,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode ?? config('telegraph.default_parse_mode', Telegraph::PARSE_HTML),
            'description' => $this->description,
        ], fn ($value) => $value !== null);
    }
}
