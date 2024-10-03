<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Entity;
use DefStudio\Telegraph\DTO\Message;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Message::fromArray([
        'message_id' => 2,
        'date' => now()->timestamp,
        'entities' => [
            [
                'type' => 'url',
                'offset' => 10,
                'length' => 19,
                'url' => 'https://example.com',
                'user' => [
                    'id' => 1,
                    'is_bot' => true,
                    'first_name' => 'a',
                    'last_name' => 'b',
                    'username' => 'c',
                    'language_code' => 'd',
                    'is_premium' => false,
                ],
                'language' => 'en',
                'custom_emoji_id' => '12345',
            ],
        ],
    ]);

    $array = $dto->entities()->first()->toArray();

    $reflection = new ReflectionClass(Entity::class);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
