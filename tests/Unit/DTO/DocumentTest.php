<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Document;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Document::fromArray([
        'file_id' => 45,
        'file_name' => 'My Document.pdf',
        'mime_type' => 'application.pdf',
        'file_size' => 42,
        'thumb' => [
            'file_id' => 99,
            'width' => 1024,
            'height' => 768,
            'file_size' => 42,
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
