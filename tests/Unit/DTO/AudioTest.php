<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Audio;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Audio::fromArray([
        'file_id' => 31,
        'duration' => 666,
        'title' => 'my audio',
        'file_name' => 'My Audio.mp3',
        'mime_type' => 'audio/mp3',
        'file_size' => 42,
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
