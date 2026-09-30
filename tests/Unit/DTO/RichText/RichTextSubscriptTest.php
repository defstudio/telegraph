<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\RichText\RichTextString;
use DefStudio\Telegraph\DTO\RichText\RichTextSubscript;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

it('export all properties', function () {
    $dto = RichTextSubscript::fromData([
        'type' => 'subscript',
        'text' => 'Hello world',
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
    $dto = RichTextSubscript::fromData([
        'type' => 'subscript',
        'text' => 'Hello world',
    ]);

    $reflection = new ReflectionClass($dto);

    $property = $reflection->getProperty('text');

    $text = $property->getValue($dto);

    expect($text)->toBeInstanceOf(RichTextString::class);
});

it('create text from array', function () {
    $dto = RichTextSubscript::fromData([
        'type' => 'subscript',
        'text' => [
            'test',
            [
                'type' => 'subscript',
                'text' => 'Hello world',
            ],
        ],
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
    $dto = RichTextSubscript::fromData([
        'type' => 'subscript',
        'text' =>
            [
                'type' => 'subscript',
                'text' => 'Hello world',
            ],

    ]);

    $reflection = new ReflectionClass($dto);

    $property = $reflection->getProperty('text');

    $text = $property->getValue($dto);

    expect($text)->toBeInstanceOf(RichTextSubscript::class);
});

it('throw exception with wrong data structure', function () {
    expect(fn () => RichTextSubscript::fromData('test'))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});

it('throw exception with wrong type', function () {
    expect(fn () => RichTextSubscript::fromData(['type' => 'test']))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});
