<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Game;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Game::fromArray([
        'title' => 'test title',
        'description' => 'test description',
        'text' => 'test text',
        'photo' => [
            [
                'file_id' => 99,
                'width' => 1024,
                'height' => 768,
                'file_size' => 42,
            ],
        ],
        'animation' => [
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
        ],
        'entities' => [
            [
                'type' => 'url',
                'offset' => 4,
                'length' => 19,
                'url' => 'https://example.com',
            ],
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
