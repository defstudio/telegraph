<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\RichText\RichTextBold;
use DefStudio\Telegraph\DTO\RichText\RichTextReference;
use DefStudio\Telegraph\DTO\RichText\RichTextString;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

it('export all properties', function () {
    $dto = RichTextReference::fromData([
        'type' => 'reference',
        'text' => 'Hello world',
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

it('create text from string', function () {
    $dto = RichTextReference::fromData([
        'type' => 'reference',
        'text' => 'Hello world',
        'name' => 'test',
    ]);

    $reflection = new ReflectionClass($dto);

    $property = $reflection->getProperty('text');

    $text = $property->getValue($dto);

    expect($text)->toBeInstanceOf(RichTextString::class);
});

it('create text from array', function () {
    $dto = RichTextReference::fromData([
        'type' => 'reference',
        'text' => [
            'test',
            [
                'type' => 'bold',
                'text' => 'Hello world',
            ],
        ],
        'name' => 'test',
    ]);

    $reflection = new ReflectionClass($dto);

    $property = $reflection->getProperty('text');

    $text = $property->getValue($dto);

    expect($text)->toBeInstanceOf(Collection::class)
        ->and(
            $text->every(
                fn ($item) => $item instanceof RichTextItem
            )
        )->toBeTrue();
});

it('create text from Rich Text Item', function () {
    $dto = RichTextReference::fromData([
        'type' => 'reference',
        'text' =>
            [
                'type' => 'bold',
                'text' => 'Hello world',
            ],
        'name' => 'test',

    ]);

    $reflection = new ReflectionClass($dto);

    $property = $reflection->getProperty('text');

    $text = $property->getValue($dto);

    expect($text)->toBeInstanceOf(RichTextBold::class);
});

it('throw exception with wrong data structure', function () {
    expect(fn () => RichTextReference::fromData('test'))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});

it('throw exception with wrong type', function () {
    expect(fn () => RichTextReference::fromData(['type' => 'test']))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});
