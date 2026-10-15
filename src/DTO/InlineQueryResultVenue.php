<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Telegraph;

class InlineQueryResultVenue extends InlineQueryResult
{
    protected string $type = 'venue';

    protected string $id;

    protected string $title;

    protected float $latitude;

    protected float $longitude;

    protected string $address;

    protected ?string $foursquareId = null;

    protected ?string $foursquareType = null;

    protected ?string $googlePlaceId = null;

    protected ?string $googlePlaceType = null;

    protected ?string $message = null;

    protected ?string $thumbUrl = null;

    protected ?int $thumbWidth = null;

    protected ?int $thumbHeight = null;

    public static function make(string $id, string $title, float $latitude, float $longitude, string $address, ?string $message = null): InlineQueryResultVenue
    {
        $result = new InlineQueryResultVenue;
        $result->id = $id;
        $result->title = $title;
        $result->latitude = $latitude;
        $result->longitude = $longitude;
        $result->address = $address;
        $result->message = $message;

        return $result;
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

    public function foursquareId(?string $foursquareId): static
    {
        $this->foursquareId = $foursquareId;

        return $this;
    }

    public function foursquareType(?string $foursquareType): static
    {
        $this->foursquareType = $foursquareType;

        return $this;
    }

    public function googlePlaceId(?string $googlePlaceId): static
    {
        $this->googlePlaceId = $googlePlaceId;

        return $this;
    }

    public function googlePlaceType(?string $googlePlaceType): static
    {
        $this->googlePlaceType = $googlePlaceType;

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
            'address' => $this->address,
            'foursquare_id' => $this->foursquareId,
            'foursquare_type' => $this->foursquareType,
            'google_place_id' => $this->googlePlaceId,
            'google_place_type' => $this->googlePlaceType,
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
