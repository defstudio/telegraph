<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Message::fromArray([
        'message_id' => 2,
        'message_thread_id' => 123456,
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
            'language_code' => 'd',
            'is_premium' => false,
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
            'animation' => [
                'file_id' => 99,
                'width' => 10,
                'height' => 20,
                'duration' => 10,
                'file_name' => 'name',
                'mime_type' => 'type',
                'file_size' => 20,
                'thumb' => [
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
            'sticker' => [
                'file_id' => 31,
                'width' => 1024,
                'height' => 768,
                'type' => 'regular',
                'is_animated' => false,
                'is_video' => false,
                'emoji' => 'test',
                'file_size' => 42,
                'thumb' => [
                    'file_id' => 99,
                    'width' => 1024,
                    'height' => 768,
                    'file_size' => 42,
                ],
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
        'animation' => [
            'file_id' => 99,
            'width' => 10,
            'height' => 20,
            'duration' => 10,
            'file_name' => 'name',
            'mime_type' => 'type',
            'file_size' => 20,
            'thumb' => [
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
        'sticker' => [
            'file_id' => 31,
            'width' => 1024,
            'height' => 768,
            'type' => 'regular',
            'is_animated' => false,
            'is_video' => false,
            'emoji' => 'test',
            'file_size' => 42,
            'thumb' => [
                'file_id' => 99,
                'width' => 1024,
                'height' => 768,
                'file_size' => 42,
            ],
        ],
        'invoice' => [
            'title' => 'Title',
            'description' => 'Description',
            'start_parameter' => 'test',
            'currency' => 'EUR',
            'total_amount' => 20,
        ],
        'successful_payment' => [
            'currency' => 'EUR',
            'total_amount' => 100,
            'invoice_payload' => 'id_10',
            'subscription_expiration_date' => 14000,
            'is_recurring' => false,
            'is_first_recurring' => false,
            'shipping_option_id' => 10,
            'order_info' => [
                'name' => 'test name',
                'phone_number' => ' + 39 333 333 3333',
                'email' => 'test@email . it',
                'shipping_address' => [
                    'country_code' => ' + 39',
                    'state' => 'italy',
                    'city' => 'rome',
                    'street_line1' => 'street test',
                    'street_line2' => '',
                    'post_code' => '00042',
                ],
            ],
            'telegram_payment_charge_id' => 10,
            'provider_payment_charge_id' => 10,
        ],
        'refunded_payment' => [
            'currency' => 'XTR',
            'total_amount' => 100,
            'invoice_payload' => 'id_10',
            'telegram_payment_charge_id' => 10,
            'provider_payment_charge_id' => 10,
        ],
        'location' => [
            'latitude' => 12456789,
            'longitude' => 98765431,
            'horizontal_accuracy' => 3,
        ],
        'venue' => [
            'location' => [
                'latitude' => 12456789,
                'longitude' => 98765431,
                'horizontal_accuracy' => 3,
            ],
            'title' => 'test title',
            'address' => 'test address',
        ],
        'contact' => [
            'phone_number' => '123456789',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'user_id' => 102030,
            'vcard' => 'fake',
        ],
        'left_chat_member' => [
            'id' => 123455,
            'is_bot' => 'false',
            'first_name' => 'Steph',
        ],
        'new_chat_members' => [
            [
                'id' => 123456,
                'is_bot' => 'false',
                'first_name' => 'John',
            ],
            [
                'id' => 123457,
                'is_bot' => 'false',
                'first_name' => 'Bob',
            ],
        ],
        'web_app_data' => [
            "button" => "CustomButton",
            "data" => "Data",
        ],
        'write_access_allowed' => [
            "from_request" => true,
            "web_app_name" => "test",
            "from_attachment_menu" => true,
        ],
        'migrate_to_chat_id' => 20,
        'entities' => [
            [
                'type' => 'url',
                'offset' => 4,
                'length' => 19,
                'url' => 'https://example.com',
            ],
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it("extract web_app_data of string type", function () {
    $dto = Message::fromArray([
        'message_id' => 2,
        'date' => now()->timestamp,
        'web_app_data' => [
            "button" => "SendString",
            "data" => "Data",
        ],
    ]);
    $webAppData = $dto->webAppData();

    expect($webAppData)->toBe("Data");
});


it("extract web_app_data of json type", function () {
    $dto = Message::fromArray([
        'message_id' => 2,
        'date' => now()->timestamp,
        'web_app_data' => [
            "button" => "SendJson",
            "data" => '[
                false,
                1,
                "string",
                {
                  "a" : "b"
                }
              ]',
        ],
    ]);
    $webAppData = $dto->webAppData();

    expect($webAppData)->toBe([
        false,
        1,
        "string",
        [
            "a" => "b",
        ],
    ]);
});
