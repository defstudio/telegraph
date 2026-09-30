<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockBlockQuotation;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockBlockQuotation::fromArray([
        'type' => 'blockquote',
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
        'credit' => [
            'type' => 'bold',
            'text' => 'Hello world',
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it('throw exception with wrong type', function () {
    expect(fn () => RichBlockBlockQuotation::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
