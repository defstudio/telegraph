<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

class InlineQueryResultVoice extends InlineQueryResult
{
    protected string $type = 'voice';
    protected string $id;
    protected string $url;
    protected string $title;
    protected string|null $caption = null;
    protected int|null $duration = null;

    public static function make(string $id, string $url, string $title): InlineQueryResultVoice
    {
        $result = new InlineQueryResultVoice();
        $result->id = $id;
        $result->url = $url;
        $result->title = $title;

        return $result;
    }

    public function caption(string|null $caption): InlineQueryResultVoice
    {
        $this->caption = $caption;

        return $this;
    }

    public function duration(int|null $duration): InlineQueryResultVoice
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
            '$voice_url' => $this->url,
            '$title' => $this->title,
            '$caption' => $this->caption,
            '$voice_duration' => $this->duration,
        ];
    }
}
