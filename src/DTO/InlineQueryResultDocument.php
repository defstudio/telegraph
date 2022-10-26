<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

class InlineQueryResultDocument extends InlineQueryResult
{
    protected string $type = 'document';
    protected string $id;
    protected string $title;
    protected string $url;
    protected string $mimeType;
    protected string|null $caption = null;
    protected string|null $description = null;
    protected string|null $thumbUrl = null;
    protected int|null $thumbWidth = null;
    protected int|null $thumbHeight = null;

    public static function make(string $id, string $title, string $url, string $mimeType): InlineQueryResultDocument
    {
        $result = new InlineQueryResultDocument();
        $result->id = $id;
        $result->title = $title;
        $result->url = $url;
        $result->mimeType = $mimeType;

        return $result;
    }

    public function caption(string|null $caption): InlineQueryResultDocument
    {
        $this->caption = $caption;

        return $this;
    }

    public function description(string|null $description): InlineQueryResultDocument
    {
        $this->description = $description;

        return $this;
    }

    public function thumbUrl(string|null $thumbUrl): InlineQueryResultDocument
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    public function thumbWidth(int|null $thumbWidth): InlineQueryResultDocument
    {
        $this->thumbWidth = $thumbWidth;

        return $this;
    }

    public function thumbHeight(int|null $thumbHeight): InlineQueryResultDocument
    {
        $this->thumbHeight = $thumbHeight;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'title' => $this->title,
            'caption' => $this->caption,
            'document_url' => $this->url,
            'mime_type' => $this->mimeType,
            'description' => $this->description,
            'thumb_url' => $this->thumbUrl,
            'thumb_width' => $this->thumbWidth,
            'thumb_height' => $this->thumbHeight,
        ];
    }
}
