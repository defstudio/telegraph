<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Video;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Video::fromArray([
        'file_id' => 31,
        'width' => 1024,
        'height' => 768,
        'duration' => 666,
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
