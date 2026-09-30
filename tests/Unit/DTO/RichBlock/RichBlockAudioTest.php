<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockAudio;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockAudio::fromArray([
        'type' => 'audio',
        'audio' => [
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
        ],
        'caption' => [
            'text' => [
                'type' => 'anchor',
                'name' => 'test',
            ],
            'credit' => [
                'type' => 'anchor',
                'name' => 'test',
            ],
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it('throw exception with wrong type', function () {
    expect(fn () => RichBlockAudio::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
