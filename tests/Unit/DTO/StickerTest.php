<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Sticker;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Sticker::fromArray([
        'file_id' => 31,
        'width' => 1024,
        'height' => 768,
        'type' => 'regular',
        'is_animated' => false,
        'is_video' => false,
        'emoji' => 'test',
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
