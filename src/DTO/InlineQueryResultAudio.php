<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

class InlineQueryResultAudio extends InlineQueryResult
{
    protected string $type = 'audio';
    protected string $id;
    protected string $url;
    protected string $title;
    protected string|null $caption = null;
    protected string|null $performer = null;
    protected int|null $duration = null;

    public static function make(string $id, string $url, string $title): InlineQueryResultAudio
    {
        $result = new InlineQueryResultAudio();
        $result->id = $id;
        $result->url = $url;
        $result->title = $title;

        return $result;
    }

    public function caption(string|null $caption): InlineQueryResultAudio
    {
        $this->caption = $caption;

        return $this;
    }

    public function performer(string|null $performer): InlineQueryResultAudio
    {
        $this->performer = $performer;

        return $this;
    }

    public function duration(int|null $duration): InlineQueryResultAudio
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
            '$audio_url' => $this->url,
            '$title' => $this->title,
            '$caption' => $this->caption,
            '$performer' => $this->performer,
            '$audio_duration' => $this->duration,
        ];
    }
}
