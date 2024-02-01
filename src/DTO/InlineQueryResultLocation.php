<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultLocation extends InlineQueryResult
{
    protected string $type = 'location';
    protected string $id;
    protected string $title;
    protected float $latitude;
    protected float $longitude;
    protected string|null $message = null;
    protected string|null $thumbUrl = null;
    protected int|null $livePeriod = null;
    protected int|null $heading = null;
    protected int|null $proximityAlertRadius = null;
    protected int|null $thumbWidth = null;
    protected int|null $thumbHeight = null;
    protected float|null $horizontalAccuracy = null;
    protected string|null $parseMode = null;

    public static function make(string $id, string $title, float $latitude, float $longitude, string $message = null): InlineQueryResultLocation
    {
        $result = new InlineQueryResultLocation();
        $result->id = $id;
        $result->title = $title;
        $result->latitude = $latitude;
        $result->longitude = $longitude;
        $result->message = $message;

        return $result;
    }

    public function thumbUrl(string|null $thumbUrl): static
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    public function livePeriod(int|null $livePeriod): static
    {
        $this->livePeriod = $livePeriod;

        return $this;
    }

    public function heading(int|null $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function proximityAlertRadius(int|null $proximityAlertRadius): static
    {
        $this->proximityAlertRadius = $proximityAlertRadius;

        return $this;
    }

    public function thumbWidth(int|null $thumbWidth): static
    {
        $this->thumbWidth = $thumbWidth;

        return $this;
    }

    public function thumbHeight(int|null $thumbHeight): static
    {
        $this->thumbHeight = $thumbHeight;

        return $this;
    }

    public function horizontalAccuracy(float|null $horizontalAccuracy): static
    {
        $this->horizontalAccuracy = $horizontalAccuracy;

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
        $data = [
            'title' => $this->title,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'thumb_url' => $this->thumbUrl,
            'live_period' => $this->livePeriod,
            'heading' => $this->heading,
            'proximity_alert_radius' => $this->proximityAlertRadius,
            'thumb_width' => $this->thumbWidth,
            'thumb_height' => $this->thumbHeight,
            'horizontal_accuracy' => $this->horizontalAccuracy,
        ];

        if($this->message !== null) {
            $data['input_message_content'] = [
                'message_text' => $this->message,
                'parse_mode' => $this->parseMode ?? config('telegraph.default_parse_mode', Telegraph::PARSE_HTML),
            ];
        }

        return array_filter($data, fn ($value) => $value !== null);
    }
}
