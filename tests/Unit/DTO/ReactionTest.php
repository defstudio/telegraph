<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Reaction;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Reaction::fromArray([
        // ...
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it('extract chat info', function () {

});

it('extract actor chat info', function () {

});

it('extract from info', function () {

});

it('extract old_reaction info', function () {

});

it('extract new_reaction info', function () {

});
