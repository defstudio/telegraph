<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Audio;
use DefStudio\Telegraph\DTO\ShippingAddress;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = ShippingAddress::fromArray([
        'country_code' => '+39',
        'state' => 'italy',
        'city' => 'rome',
        'street_line1' => 'street test',
        'street_line2' => '',
        'post_code' => '00042',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
