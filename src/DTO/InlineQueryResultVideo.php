<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

class InlineQueryResultVideo extends InlineQueryResult
{
    protected string $type = 'video';
    protected string $id;
    protected string $videoUrl;
    protected string $mimeType;
    protected string $thumbUrl;
    protected string $title;
    protected string|null $caption = null;
    protected string|null $description = null;
    protected int|null $videoWidth = null;
    protected int|null $videoHeight = null;
    protected int|null $videoDuration = null;

    public static function make(string $id, string $videoUrl, string $mimeType, string $thumbUrl, string $title): InlineQueryResultVideo
    {
        $result = new InlineQueryResultVideo();
        $result->id = $id;
        $result->videoUrl = $videoUrl;
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

    public function videoWidth(int|null $videoWidth): InlineQueryResultVideo
    {
        $this->videoWidth = $videoWidth;

        return $this;
    }

    public function videoHeight(int|null $videoHeight): InlineQueryResultVideo
    {
        $this->videoHeight = $videoHeight;

        return $this;
    }

    public function videoDuration(int|null $videoDuration): InlineQueryResultVideo
    {
        $this->videoDuration = $videoDuration;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'title' => $this->title,
            '$video_url' => $this->videoUrl,
            '$mime_type' => $this->mimeType,
            '$thumb_url' => $this->thumbUrl,
            '$title' => $this->title,
            '$caption' => $this->caption,
            '$description' => $this->description,
            '$video_width' => $this->videoWidth,
            '$video_height' => $this->videoHeight,
            '$video_duration' => $this->videoDuration,
        ];
    }
}
