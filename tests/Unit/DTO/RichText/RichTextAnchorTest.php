<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichText\RichTextAnchor;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Str;

it('export all properties', function () {
    $dto = RichTextAnchor::fromData([
        'type' => 'anchor',
        'name' => 'test',
    ]);

    $reflection = new ReflectionClass($dto);

    $array = $dto->build();

    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(
            Str::of($property->name)->snake()->toString()
        );
    }
});

it('throw exception with wrong data structure', function () {
    expect(fn () => RichTextAnchor::fromData('test'))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});

it('throw exception with wrong type', function () {
    expect(fn () => RichTextAnchor::fromData(['type' => 'test']))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});
