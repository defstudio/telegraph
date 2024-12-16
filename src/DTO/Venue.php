<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, float>
 */
class Venue implements Arrayable
{
    private Location $location;
    private string $title;
    private string $address;
    private ?string $foursquareId = null;
    private ?string $foursquareType = null;
    private ?string $googlePlaceId = null;
    private ?string $googlePlaceType = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     location: array{longitude: float, latitude: float, horizontal_accuracy?: float},
     *     title: string,
     *     address: string,
     *     foursquare_id?: string,
     *     foursquare_type?: string,
     *     google_place_id?: string,
     *     google_place_type?: string
     * } $data
     */
    public static function fromArray(array $data): Venue
    {
        $venue = new self();

        $venue->location = Location::fromArray($data['location']);
        $venue->title = $data['title'];
        $venue->address = $data['address'];
        $venue->foursquareId = $data['foursquare_id'] ?? null;
        $venue->foursquareType = $data['foursquare_type'] ?? null;
        $venue->googlePlaceId = $data['google_place_id'] ?? null;
        $venue->googlePlaceType = $data['google_place_type'] ?? null;

        return $venue;
    }

    public function location(): Location
    {
        return $this->location;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function address(): string
    {
        return $this->address;
    }

    public function foursquareId(): ?string
    {
        return $this->foursquareId;
    }

    public function foursquareType(): ?string
    {
        return $this->foursquareType;
    }

    public function googlePlaceId(): ?string
    {
        return $this->googlePlaceId;
    }

    public function googlePlaceType(): ?string
    {
        return $this->googlePlaceType;
    }

    public function toArray(): array
    {
        return array_filter([
            'location' => $this->location,
            'title' => $this->title,
            'address' => $this->address,
            'foursquare_id' => $this->foursquareId,
            'foursquare_type' => $this->foursquareType,
            'google_place_id' => $this->googlePlaceId,
            'google_place_type' => $this->googlePlaceType,
        ], fn ($value) => $value !== null);
    }
}
