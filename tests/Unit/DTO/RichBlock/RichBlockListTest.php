<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockList;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockList::fromArray([
        'type' => 'list',
        'items' => [
            [
                'label' => 'test',
                'blocks' => [
                    [
                        'type' => 'anchor',
                        'name' => 'test',
                    ],
                ],
                'has_checkbox' => true,
                'is_checked' => true,
                'value' => 1,
                'type' => 'a',
            ],
            [
                'label' => 'test2',
                'blocks' => [
                    [
                        'type' => 'anchor',
                        'name' => 'test',
                    ],
                ],
                'has_checkbox' => true,
                'is_checked' => true,
                'value' => 1,
                'type' => 'a',
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
    expect(fn () => RichBlockList::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
