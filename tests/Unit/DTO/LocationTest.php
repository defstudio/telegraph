<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Location;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Location::fromArray([
        'latitude' => 12456789,
        'longitude' => 98765431,
        'horizontal_accuracy' => 3,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
