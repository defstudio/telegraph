<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultArticle extends InlineQueryResult
{
    protected string $type = 'article';

    protected string $id;

    protected string $title;

    protected ?string $message = null;

    protected ?string $url = null;

    protected ?string $description = null;

    protected ?string $thumbUrl = null;

    protected ?int $thumbWidth = null;

    protected ?int $thumbHeight = null;

    protected ?bool $hideUrl = null;

    protected ?string $parseMode = null;

    public static function make(string $id, string $title, ?string $message = null): InlineQueryResultArticle
    {
        $result = new InlineQueryResultArticle;
        $result->id = $id;
        $result->title = $title;
        $result->message = $message;

        return $result;
    }

    public function url(?string $url): static
    {
        $this->url = $url;

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

    public function hideUrl(?bool $hideUrl): static
    {
        $this->hideUrl = $hideUrl;

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
        $data = [
            'title' => $this->title,
            'url' => $this->url,
            'hide_url' => $this->hideUrl,
            'description' => $this->description,
            'thumbnail_url' => $this->thumbUrl,
            'thumbnail_width' => $this->thumbWidth,
            'thumbnail_height' => $this->thumbHeight,
        ];

        if ($this->message !== null) {
            $data['input_message_content'] = [
                'message_text' => $this->message,
                'parse_mode' => $this->parseMode ?? config('telegraph.default_parse_mode', Telegraph::PARSE_HTML),
            ];
        }

        return array_filter($data, fn ($value) => $value !== null);
    }
}
