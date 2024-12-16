<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Sticker;
use DefStudio\Telegraph\DTO\Venue;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Venue::fromArray([
        'location' => [
            'latitude' => 10,
            'longitude' => 10,
            'horizontal_accuracy' => 10,
        ],
        'title' => 'test title',
        'address' => 'test address',
        'foursquare_id' => 'test foursquare_id',
        'foursquare_type' => 'test foursquare_type',
        'google_place_id' => 'test google_place_id',
        'google_place_type' => 'test google_place_type',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
