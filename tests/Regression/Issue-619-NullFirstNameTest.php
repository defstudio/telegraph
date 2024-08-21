<?php

use DefStudio\Telegraph\DTO\User;
use Illuminate\Support\Str;

test('null first_name for user dto', function () {
    $dto = User::fromArray([
        'id' => 1,
        'is_bot' => false,
        'first_name' => null,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
