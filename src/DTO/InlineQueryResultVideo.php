<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultVideo extends InlineQueryResult
{
    protected string $type = 'video';
    protected string $id;
    protected string $url;
    protected string $mimeType;
    protected string $thumbUrl;
    protected string $title;
    protected string|null $caption = null;
    protected string|null $description = null;
    protected int|null $width = null;
    protected int|null $height = null;
    protected int|null $duration = null;
    protected string|null $parseMode = null;

    public static function make(string $id, string $url, string $mimeType, string $thumbUrl, string $title): InlineQueryResultVideo
    {
        $result = new InlineQueryResultVideo();
        $result->id = $id;
        $result->url = $url;
        $result->mimeType = $mimeType;
        $result->thumbUrl = $thumbUrl;
        $result->title = $title;

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
            'video_url' => $this->url,
            'mime_type' => $this->mimeType,
            'thumb_url' => $this->thumbUrl,
            'title' => $this->title,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode ?? config('telegraph.default_parse_mode', Telegraph::PARSE_HTML),
            'video_width' => $this->width,
            'video_height' => $this->height,
            'video_duration' => $this->duration,
            'description' => $this->description,
        ], fn ($value) => $value !== null);
    }
}
