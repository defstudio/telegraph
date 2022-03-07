<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\User;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = User::fromArray([
        'id' => 1,
        'is_bot' => true,
        'first_name' => 'a',
        'last_name' => 'b',
        'username' => 'c',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
