<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockDetails;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockDetails::fromArray([
        'type' => 'details',
        'summary' => [
            'type' => 'anchor',
            'name' => 'test',
        ],
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
        'is_open' => true,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it('throw exception with wrong type', function () {
    expect(fn () => RichBlockDetails::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
