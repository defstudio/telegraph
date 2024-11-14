<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\ChatJoinRequest;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = ChatJoinRequest::fromArray([
        'user_chat_id' => 2,
        'bio' => 'bio',
        'date' => now()->timestamp,
        'invite_link' => [
            'invite_link' => 'https:/t.me/+EEEEEEE...',
            'creator' => [
                'id' => 1,
                'is_bot' => false,
                'first_name' => 'aa',
                'last_name' => 'bb',
                'username' => 'cc',
                'language_code' => 'dd',
                'is_premium' => false,
            ],
            'creates_join_request' => true,
            'is_primary' => false,
            'is_revoked' => false,
        ],
        'chat' => [
            'id' => 3,
            'type' => 'a',
            'title' => 'b',
        ],
        'from' => [
            'id' => 2,
            'is_bot' => true,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
