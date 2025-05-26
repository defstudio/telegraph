<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\ChatMemberUpdate;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = ChatMemberUpdate::fromArray([
        'chat' => [
            'id' => 123456789,
            'first_name' => 'Mario',
            'type' => 'private',
        ],
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Mario',
            'language_code' => 'it',
        ],
        'date' => 123456789,
        'old_chat_member' => [
            'user' => [
                'id' => 1111111,
                'is_bot' => true,
                'first_name' => 'Bot',
                'username' => 'MarioBot',
            ],
            'status' => 'member',
        ],
        'new_chat_member' => [
            'user' => [
                'id' => 2222222,
                'is_bot' => true,
                'first_name' => 'Bot',
                'username' => 'MarioBot',
            ],
            'status' => 'kicked',
            'until_date' => 0,
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
