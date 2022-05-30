<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\TelegramUpdate;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = TelegramUpdate::fromArray([
        'update_id' => 1,
        'message' => [
            'message_id' => 2,
            'date' => now()->timestamp,
            'text' => 'f',
        ],
        'channel_post' => [
            'message_id' => 4,
            'date' => now()->timestamp,
            'text' => 'f',
        ],
        'callback_query' => [
            'id' => 3,
            'from' => [
                'id' => 1,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
            ],
        ],
        'inline_query' => [
          'id' => "a99",
          'query' => 'foo',
          'from' => [
              'id' => 1,
              'is_bot' => false,
              'first_name' => 'e',
              'last_name' => 'f',
              'username' => 'g',
          ],
          'offset' => '+4',
          'chat_type' => 'private',
        ],
        'my_chat_member' => [
            'from' => [
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
            'date' => now()->timestamp,
            'old_chat_member' => [
                'status' => 'a',
                'user' => [
                      'id' => 1,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
                ],
                'is_anonymous' => false,
            ],
            'new_chat_member' => [
                'status' => 'a',
                'user' => [
                      'id' => 1,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
                ],
                'is_anonymous' => false,
            ],
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
