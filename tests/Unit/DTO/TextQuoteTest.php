<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\TextQuote;
use Illuminate\Support\Str;

it('export all properties to array', function() {
    $dto = TextQuote::fromArray([
        'text' => 'test quote',
        'entities' => [
            [
                'type' => 'url',
                'offset' => 4,
                'length' => 19,
                'url' => 'https://example.com',
            ],
        ],
        'position' => 1,
        'is_manual' => true,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
