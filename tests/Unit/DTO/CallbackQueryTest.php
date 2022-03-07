<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\TelegramUpdate;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = CallbackQuery::fromArray([
        'id' => 3,
        'from' => [
            'id' => 1,
            'is_bot' => true,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
        ],
        'message' => [
            'message_id' => 2,
            'date' => now()->timestamp,
            'text' => 'f',
        ],
        'data' => 'action:test',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
