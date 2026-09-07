<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockTableCell;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockTableCell::fromArray([
        'text' => [
            'type' => 'anchor',
            'name' => 'test',
        ],
        'is_header' => true,
        'colspan' => 1,
        'rowspan' => 1,
        'align' => 'left',
        'valign' => 'top',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
