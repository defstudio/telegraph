<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

class InlineQueryResultMpeg4Gif extends InlineQueryResult
{
    protected string $type = 'mpeg4_gif';
    protected string $id;
    protected string $mpeg4Url;
    protected string $thumbUrl;
    protected int|null $mpeg4Width = null;
    protected int|null $mpeg4Height = null;
    protected int|null $mpeg4Duration = null;
    protected string|null $thumbMimeType = null;
    protected string|null $title = null;
    protected string|null $caption = null;

    public static function make(string $id, string $mpeg4Url, string $thumbUrl): InlineQueryResultMpeg4Gif
    {
        $result = new InlineQueryResultMpeg4Gif();
        $result->id = $id;
        $result->mpeg4Url = $mpeg4Url;
        $result->thumbUrl = $thumbUrl;

        return $result;
    }

    public function mpeg4Width(int|null $mpeg4Width): InlineQueryResultMpeg4Gif
    {
        $this->mpeg4Width = $mpeg4Width;

        return $this;
    }

    public function mpeg4Height(int|null $mpeg4Height): InlineQueryResultMpeg4Gif
    {
        $this->mpeg4Height = $mpeg4Height;

        return $this;
    }

    public function mpeg4Duration(int|null $mpeg4Duration): InlineQueryResultMpeg4Gif
    {
        $this->mpeg4Duration = $mpeg4Duration;

        return $this;
    }

    public function thumbMimeType(string|null $thumbMimeType): InlineQueryResultMpeg4Gif
    {
        $this->thumbMimeType = $thumbMimeType;

        return $this;
    }

    public function title(string|null $title): InlineQueryResultMpeg4Gif
    {
        $this->title = $title;

        return $this;
    }

    public function caption(string|null $caption): InlineQueryResultMpeg4Gif
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'mpeg4_url' => $this->mpeg4Url,
            'mpeg4_width' => $this->mpeg4Width,
            'mpeg4_height' => $this->mpeg4Height,
            'mpeg4_duration' => $this->mpeg4Duration,
            'thumb_url' => $this->thumbUrl,
            'thumb_mime_type' => $this->thumbMimeType,
            'title' => $this->title,
            'caption' => $this->caption,
        ];
    }
}
