<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Audio;
use DefStudio\Telegraph\DTO\OrderInfo;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = OrderInfo::fromArray([
        'name' => 'test name',
        'phone_number' => '+39 333 333 3333',
        'email' => 'test@email.it',
        'shipping_address' => [
            'country_code' => '+39',
            'state' => 'italy',
            'city' => 'rome',
            'street_line1' => 'street test',
            'street_line2' => '',
            'post_code' => '00042',
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
