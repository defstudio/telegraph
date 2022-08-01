<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Message::fromArray([
        'message_id' => 2,
        'date' => now()->timestamp,
        'edit_date' => now()->timestamp,
        'text' => 'f',
        'has_protected_content' => true,
        'from' => [
            'id' => 1,
            'is_bot' => true,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
        ],
        'forward_from' => [
            'id' => 1,
            'is_bot' => true,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
        ],
        'chat' => [
            'id' => 3,
            'type' => 'a',
            'title' => 'b',
        ],
        'reply_markup' => [
            'inline_keyboard' => Keyboard::make()->button('test')->url('a')->toArray(),
        ],
        'reply_to_message' => [
            'message_id' => 3,
            'date' => now()->timestamp,
            'edit_date' => now()->timestamp,
            'text' => 'f',
            'has_protected_content' => true,
            'from' => [
                'id' => 1,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
            ],
            'forward_from' => [
                'id' => 1,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
            ],
            'chat' => [
                'id' => 3,
                'type' => 'a',
                'title' => 'b',
            ],
            'reply_markup' => [
                'inline_keyboard' => Keyboard::make()->button('test')->url('a')->toArray(),
            ],
            'photo' => [
                [
                    'file_id' => 99,
                    'width' => 1024,
                    'height' => 768,
                    'file_size' => 42,
                ],
            ],
            'audio' => [
                'file_id' => 31,
                'duration' => 666,
                'title' => 'my audio',
                'file_name' => 'My Audio.mp3',
                'mime_type' => 'audio/mp3',
                'file_size' => 42,
                'thumb' => [
                    'file_id' => 99,
                    'width' => 1024,
                    'height' => 768,
                    'file_size' => 42,
                ],
            ],
            'document' => [
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
            ],
            'video' => [
                'file_id' => 31,
                'width' => 1024,
                'height' => 768,
                'duration' => 666,
                'file_name' => 'My Audio.mp3',
                'mime_type' => 'audio/mp3',
                'file_size' => 42,
                'thumb' => [
                    'file_id' => 99,
                    'width' => 1024,
                    'height' => 768,
                    'file_size' => 42,
                ],
            ],
            'voice' => [
                'file_id' => 31,
                'duration' => 666,
                'mime_type' => 'audio/mp3',
                'file_size' => 42,
            ],
            'location' => [
                'latitude' => 12456789,
                'longitude' => 98765431,
                'horizontal_accuracy' => 3,
            ],
            'contact' => [
                'phone_number' => '123456789',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'user_id' => 102030,
                'vcard' => 'fake',
            ],
        ],
        'photo' => [
            [
                'file_id' => 99,
                'width' => 1024,
                'height' => 768,
                'file_size' => 42,
            ],
        ],
        'audio' => [
            'file_id' => 31,
            'duration' => 666,
            'title' => 'my audio',
            'file_name' => 'My Audio.mp3',
            'mime_type' => 'audio/mp3',
            'file_size' => 42,
            'thumb' => [
                'file_id' => 99,
                'width' => 1024,
                'height' => 768,
                'file_size' => 42,
            ],
        ],
        'document' => [
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
        ],
        'video' => [
            'file_id' => 31,
            'width' => 1024,
            'height' => 768,
            'duration' => 666,
            'file_name' => 'My Audio.mp3',
            'mime_type' => 'audio/mp3',
            'file_size' => 42,
            'thumb' => [
                'file_id' => 99,
                'width' => 1024,
                'height' => 768,
                'file_size' => 42,
            ],
        ],
        'voice' => [
            'file_id' => 31,
            'duration' => 666,
            'mime_type' => 'audio/mp3',
            'file_size' => 42,
        ],
        'location' => [
            'latitude' => 12456789,
            'longitude' => 98765431,
            'horizontal_accuracy' => 3,
        ],
        'contact' => [
            'phone_number' => '123456789',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'user_id' => 102030,
            'vcard' => 'fake',
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
