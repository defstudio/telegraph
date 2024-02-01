<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultGif extends InlineQueryResult
{
    protected string $type = 'gif';
    protected string $id;
    protected string $url;
    protected string $thumbUrl;
    protected int|null $width = null;
    protected int|null $height = null;
    protected int|null $duration = null;
    protected string|null $title = null;
    protected string|null $caption = null;
    protected string|null $parseMode = null;

    public static function make(string $id, string $url, string $thumbUrl): InlineQueryResultGif
    {
        $result = new InlineQueryResultGif();
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

    public function duration(int|null $duration): static
    {
        $this->duration = $duration;

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
            'gif_url' => $this->url,
            'thumb_url' => $this->thumbUrl,
            'gif_width' => $this->width,
            'gif_height' => $this->height,
            'gif_duration' => $this->duration,
            'title' => $this->title,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode ?? config('telegraph.default_parse_mode', Telegraph::PARSE_HTML),
        ], fn ($value) => $value !== null);
    }
}
