<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

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

    public static function make(string $id, string $url, string $thumbUrl): InlineQueryResultGif
    {
        $result = new InlineQueryResultGif();
        $result->id = $id;
        $result->url = $url;
        $result->thumbUrl = $thumbUrl;

        return $result;
    }

    public function width(int|null $width): InlineQueryResultGif
    {
        $this->width = $width;

        return $this;
    }

    public function height(int|null $height): InlineQueryResultGif
    {
        $this->height = $height;

        return $this;
    }

    public function duration(int|null $duration): InlineQueryResultGif
    {
        $this->duration = $duration;

        return $this;
    }

    public function title(string|null $title): InlineQueryResultGif
    {
        $this->title = $title;

        return $this;
    }

    public function caption(string|null $caption): InlineQueryResultGif
    {
        $this->caption = $caption;

        return $this;
    }

    public function data(): array
    {
        return [
            'id' => $this->id,
            'gif_url' => $this->url,
            'thumb_url' => $this->thumbUrl,
            'gif_width' => $this->width,
            'gif_height' => $this->height,
            'gif_duration' => $this->duration,
            'title' => $this->title,
            'caption' => $this->caption,
        ];
    }
}
