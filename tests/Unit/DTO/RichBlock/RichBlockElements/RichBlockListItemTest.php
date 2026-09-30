<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockListItem;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockListItem::fromArray([
        'label' => 'test',
        'blocks' => [
            [
                'type' => 'anchor',
                'name' => 'test',
            ],
        ],
        'has_checkbox' => true,
        'is_checked' => true,
        'value' => 1,
        'type' => 'a',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
