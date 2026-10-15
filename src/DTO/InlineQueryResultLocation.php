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

    protected ?string $message = null;

    protected ?string $thumbUrl = null;

    protected ?int $livePeriod = null;

    protected ?int $heading = null;

    protected ?int $proximityAlertRadius = null;

    protected ?int $thumbWidth = null;

    protected ?int $thumbHeight = null;

    protected ?float $horizontalAccuracy = null;

    protected ?string $parseMode = null;

    public static function make(string $id, string $title, float $latitude, float $longitude, ?string $message = null): InlineQueryResultLocation
    {
        $result = new InlineQueryResultLocation;
        $result->id = $id;
        $result->title = $title;
        $result->latitude = $latitude;
        $result->longitude = $longitude;
        $result->message = $message;

        return $result;
    }

    public function thumbUrl(?string $thumbUrl): static
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    public function livePeriod(?int $livePeriod): static
    {
        $this->livePeriod = $livePeriod;

        return $this;
    }

    public function heading(?int $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function proximityAlertRadius(?int $proximityAlertRadius): static
    {
        $this->proximityAlertRadius = $proximityAlertRadius;

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

    public function horizontalAccuracy(?float $horizontalAccuracy): static
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
     * {@inheritDoc}
     */
    public function data(): array
    {
        $data = [
            'title' => $this->title,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'thumbnail_url' => $this->thumbUrl,
            'live_period' => $this->livePeriod,
            'heading' => $this->heading,
            'proximity_alert_radius' => $this->proximityAlertRadius,
            'thumbnail_width' => $this->thumbWidth,
            'thumbnail_height' => $this->thumbHeight,
            'horizontal_accuracy' => $this->horizontalAccuracy,
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
