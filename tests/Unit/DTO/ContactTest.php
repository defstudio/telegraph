<?php

/** @noinspection PhpUnhandledExceptionInspection */

use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Location::fromArray([
        'phone_number' => '123456789',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'user_id' => 102030,
        'vcard' => 'fake',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
