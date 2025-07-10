<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\PollOption;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = PollOption::fromArray([
        'text' => 'text option',
        'voter_count' => 1,
        'text_entities' => [
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
