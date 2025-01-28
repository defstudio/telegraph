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
        'edited_message' => [
            'message_id' => 2,
            'date' => now()->timestamp,
            'text' => 'f',
        ],
        'message_reaction' => [
            'message_id' => 2,
            'date' => now()->timestamp,
            'chat' => [
                'id' => 3,
                'type' => 'a',
                'title' => 'b',
            ],
            'actor_chat' => [
                'id' => 3,
                'type' => 'a',
                'title' => 'b',
            ],
            'user' => [
                'id' => 1,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
            ],
            'new_reaction' => [
                [
                    'type' => 'emoji',
                    'emoji' => '👍',
                ],
            ],
            'old_reaction' => [
                [
                    'type' => 'emoji',
                    'emoji' => '🔥',
                ],
            ],
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
        'pre_checkout_query' => [
            'id' => 3,
            'from' => [
                'id' => 1,
                'is_bot' => true,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c',
            ],
            'currency' => 'EUR',
            'total_amount' => '100',
            'invoice_payload' => 'test payload',
            'shipping_option_id' => 'test id',
            'order_info' => [
                'name' => 'test name',
                'phone_number' => '+39 333 333 3333',
                'email' => 'test@email.it',
                'shipping_address' => [
                    'country_code' => '+39',
                    'state' => 'italy',
                    'city' => 'rome',
                    'street_line1' => 'street test',
                    'street_line2' => '',
                    'post_code' => '00042',
                ],
            ]
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
