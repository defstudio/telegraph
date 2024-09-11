<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Reaction;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Reaction::fromArray([
        'type' => 'emoji',
        'emoji' => "👍",
        'custom_emoji_id' => 'customEmojiId',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
