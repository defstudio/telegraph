<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\PollAnswer;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = PollAnswer::fromArray([
        'poll_id' => '10',
        'user' => [
            'id' => 1,
            'is_bot' => true,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
        'voter_chat' => [
            'id' => 3,
            'type' => 'a',
            'title' => 'b',
        ],
        'option_ids' => [0, 1, 2],

    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
