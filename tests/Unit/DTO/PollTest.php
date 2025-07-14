<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Poll;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Poll::fromArray([
        'id' => '1234567',
        'question' => 'it true or false?',
        'question_entities' => [
            [
                'type' => 'url',
                'offset' => 4,
                'length' => 19,
                'url' => 'https://example.com',
            ],
        ],
        'options' => [
            [
                'text' => 'true',
                'voter_count' => 1,
            ],
            [
                'text' => 'false',
                'voter_count' => 1,
            ],
        ],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => false,
        'type' => 'quiz',
        'allows_multiple_answers' => false,

    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
