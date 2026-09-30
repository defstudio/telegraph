<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockAnimation;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockAnimation::fromArray([
        'type' => 'animation',
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
        'has_spoiler' => true,
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
    expect(fn () => RichBlockAnimation::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
