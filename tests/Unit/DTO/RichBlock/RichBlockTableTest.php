<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockTable;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockTable::fromArray([
        'type' => 'table',
        'cells' => [
            [
                [
                    'text' => [
                        'type' => 'anchor',
                        'name' => 'test',
                    ],
                    'is_header' => true,
                    'colspan' => 1,
                    'rowspan' => 1,
                    'align' => 'left',
                    'valign' => 'top',
                ],
                [
                    'text' => [
                        'type' => 'anchor',
                        'name' => 'test',
                    ],
                    'is_header' => true,
                    'colspan' => 1,
                    'rowspan' => 1,
                    'align' => 'left',
                    'valign' => 'top',
                ],
            ],
            [
                [
                    'text' => [
                        'type' => 'anchor',
                        'name' => 'test',
                    ],
                    'is_header' => true,
                    'colspan' => 1,
                    'rowspan' => 1,
                    'align' => 'left',
                    'valign' => 'top',
                ],
                [
                    'text' => [
                        'type' => 'anchor',
                        'name' => 'test',
                    ],
                    'is_header' => true,
                    'colspan' => 1,
                    'rowspan' => 1,
                    'align' => 'left',
                    'valign' => 'top',
                ],
            ],
        ],
        'is_bordered' => true,
        'is_striped' => true,
        'caption' => [
            'type' => 'anchor',
            'name' => 'test',
        ],

    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it('throw exception with wrong type', function () {
    expect(fn () => RichBlockTable::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
