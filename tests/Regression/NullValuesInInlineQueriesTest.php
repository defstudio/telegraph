<?php

use DefStudio\Telegraph\DTO\InlineQuery;

test('null value for query', function () {
    $dto = InlineQuery::fromArray([
        'id' => "a99",
        'chat_type' => 'private',
        'query' => null,
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

    expect($dto->query())->toBe("");
});

test('null value for offset', function () {
    $dto = InlineQuery::fromArray([
        'id' => "a99",
        'chat_type' => 'private',
        'query' => 'test',
        'from' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'e',
            'last_name' => 'f',
            'username' => 'g',
        ],
        'offset' => null,
        'location' => [
            'latitude' => 12456789,
            'longitude' => 98765431,
            'horizontal_accuracy' => 3,
        ],
    ]);

    expect($dto->offset())->toBe("");
});
