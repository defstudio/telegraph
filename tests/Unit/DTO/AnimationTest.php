<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Animation;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Animation::fromArray([
        'file_id' => 99,
        'width' => 10,
        'height' => 20,
        'duration' => 10,
        'file_name' => 'name',
        'mime_type' => 'type',
        'file_size' => 20,
        'thumb' => [
            'file_id' => 99,
            'width' => 1024,
            'height' => 768,
            'file_size' => 42,
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
