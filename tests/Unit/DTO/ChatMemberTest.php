<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\ChatMember;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = ChatMember::fromArray([
        'user' => [
            'id' => 2222222,
            'is_bot' => true,
            'first_name' => 'Bot',
            'username' => 'MarioBot',
        ],
        'status' => 'kicked',
        'until_date' => 0,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
