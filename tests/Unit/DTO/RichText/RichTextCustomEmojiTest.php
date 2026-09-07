<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichText\RichTextCustomEmoji;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Str;

it('export all properties', function () {
    $dto = RichTextCustomEmoji::fromData([
        'type' => 'custom_emoji',
        'custom_emoji_id' => '10',
        'alternative_text' => 'emoji',
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
    expect(fn () => RichTextCustomEmoji::fromData('test'))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});

it('throw exception with wrong type', function () {
    expect(fn () => RichTextCustomEmoji::fromData(['type' => 'test']))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});
