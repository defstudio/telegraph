<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Location implements Arrayable
{
    private float $latitude;
    private float $longitude;
    private ?float $accuracy = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     longitude: float,
     *     latitude: float,
     *     horizontal_accuracy?: float
     * } $data
     */
    public static function fromArray(array $data): Location
    {
        $location = new self();

        $location->latitude = $data['latitude'];
        $location->longitude = $data['longitude'];
        $location->accuracy = $data['horizontal_accuracy'] ?? null;

        return $location;
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }

    public function accuracy(): ?float
    {
        return $this->accuracy;
    }

    public function toArray(): array
    {
        return array_filter([
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'accuracy' => $this->accuracy,
        ], fn ($value) => $value !== null);
    }
}
