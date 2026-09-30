<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockSlideshow;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockSlideshow::fromArray([
        'type' => 'slideshow',
        'blocks' => [
            [
                'type' => 'anchor',
                'name' => 'test',
            ],
            [
                'type' => 'anchor',
                'name' => 'test',
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
    expect(fn () => RichBlockSlideshow::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
