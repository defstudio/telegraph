<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\TelegramUpdate;
use DefStudio\Telegraph\DTO\User;
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
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
