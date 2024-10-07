<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Invoice;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Invoice::fromArray([
        'title' => 'test title',
        'description' => 'test description',
        'start_parameter' => 'test',
        'currency' => 'EUR',
        'total_amount' => 20,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
