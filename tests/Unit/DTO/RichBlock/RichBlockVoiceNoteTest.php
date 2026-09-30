<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockVoiceNote;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockVoiceNote::fromArray([
        'type' => 'voice_note',
        'voice_note' => [
            'file_id' => 31,
            'duration' => 666,
            'mime_type' => 'audio/mp3',
            'file_size' => 42,
        ],
        'caption' => [
            'text' => [
                'type' => 'anchor',
                'name' => 'test',
            ],
            'credit' => [
                'type' => 'anchor',
                'name' => 'test',
            ],
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it('throw exception with wrong type', function () {
    expect(fn () => RichBlockVoiceNote::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
