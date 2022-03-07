<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Chat;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Chat::fromArray([
        'id' => 3,
        'type' => 'a',
        'title' => 'b',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
