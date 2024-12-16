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
    protected string|null $foursquareId = null;
    protected string|null $foursquareType = null;
    protected string|null $googlePlaceId = null;
    protected string|null $googlePlaceType = null;
    protected string|null $message = null;
    protected string|null $thumbUrl = null;
    protected int|null $thumbWidth = null;
    protected int|null $thumbHeight = null;

    public static function make(string $id, string $title, float $latitude, float $longitude, string $address, string $message = null): InlineQueryResultVenue
    {
        $result = new InlineQueryResultVenue();
        $result->id = $id;
        $result->title = $title;
        $result->latitude = $latitude;
        $result->longitude = $longitude;
        $result->address = $address;
        $result->message = $message;

        return $result;
    }

    public function thumbUrl(string|null $thumbUrl): static
    {
        $this->thumbUrl = $thumbUrl;

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

    public function foursquareId(string|null $foursquareId): static
    {
        $this->foursquareId = $foursquareId;

        return $this;
    }

    public function foursquareType(string|null $foursquareType): static
    {
        $this->foursquareType = $foursquareType;

        return $this;
    }

    public function googlePlaceId(string|null $googlePlaceId): static
    {
        $this->googlePlaceId = $googlePlaceId;

        return $this;
    }

    public function googlePlaceType(string|null $googlePlaceType): static
    {
        $this->googlePlaceType = $googlePlaceType;

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
            'address' => $this->address,
            'foursquare_id' => $this->foursquareId,
            'foursquare_type' => $this->foursquareType,
            'google_place_id' => $this->googlePlaceId,
            'google_place_type' => $this->googlePlaceType,
            'thumb_url' => $this->thumbUrl,
            'thumb_width' => $this->thumbWidth,
            'thumb_height' => $this->thumbHeight,
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
