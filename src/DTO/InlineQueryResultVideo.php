<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

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

    public function caption(string|null $caption): InlineQueryResultVideo
    {
        $this->caption = $caption;

        return $this;
    }

    public function description(string|null $description): InlineQueryResultVideo
    {
        $this->description = $description;

        return $this;
    }

    public function width(int|null $width): InlineQueryResultVideo
    {
        $this->width = $width;

        return $this;
    }

    public function height(int|null $height): InlineQueryResultVideo
    {
        $this->height = $height;

        return $this;
    }

    public function duration(int|null $duration): InlineQueryResultVideo
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'video_url' => $this->url,
            'mime_type' => $this->mimeType,
            'thumb_url' => $this->thumbUrl,
            'title' => $this->title,
            'caption' => $this->caption,
            'video_width' => $this->width,
            'video_height' => $this->height,
            'video_duration' => $this->duration,
            'description' => $this->description,
        ];
    }
}
