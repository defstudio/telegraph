<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockCaption;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockCaption::fromArray([
        'text' => [
            'type' => 'anchor',
            'name' => 'test',
        ],
        'credit' => [
            'type' => 'anchor',
            'name' => 'test',
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
