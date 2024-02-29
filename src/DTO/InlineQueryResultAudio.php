<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultAudio extends InlineQueryResult
{
    protected string $type = 'audio';
    protected string $id;
    protected string $url;
    protected string $title;
    protected string|null $caption = null;
    protected string|null $performer = null;
    protected int|null $duration = null;
    protected string|null $parseMode = null;

    public static function make(string $id, string $url, string $title): InlineQueryResultAudio
    {
        $result = new InlineQueryResultAudio();
        $result->id = $id;
        $result->url = $url;
        $result->title = $title;

        return $result;
    }

    public function caption(string|null $caption): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function performer(string|null $performer): static
    {
        $this->performer = $performer;

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
            'audio_url' => $this->url,
            'title' => $this->title,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode ?? config('telegraph.default_parse_mode', Telegraph::PARSE_HTML),
            'performer' => $this->performer,
            'audio_duration' => $this->duration,
        ], fn ($value) => $value !== null);
    }
}
