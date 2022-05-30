<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\InlineQuery;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = InlineQuery::fromArray([
        'id' => "a99",
        'chat_type' => 'private',
        'query' => 'foo',
        'from' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'e',
            'last_name' => 'f',
            'username' => 'g',
        ],
        'offset' => '+4',
        'location' => [
            'latitude' => 12456789,
            'longitude' => 98765431,
            'horizontal_accuracy' => 3,
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
