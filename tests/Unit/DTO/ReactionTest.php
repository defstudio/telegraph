<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Chat;
use DefStudio\Telegraph\DTO\Reaction;
use DefStudio\Telegraph\DTO\User;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Reaction::fromArray([
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
        'date' => 1727211008,
        'user' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
        'message_id' => 2,
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
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it('extract chat info', function () {
    $dto = Reaction::fromArray([
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
        'date' => 1727211008,
        'user' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
        'message_id' => 2,
        'new_reaction' => [
            [
                'type' => 'emoji',
                'emoji' => '👍',
            ],
        ],
        'old_reaction' => [],
    ]);

    expect($dto->chat())
        ->toBeInstanceOf(Chat::class)
        ->id()->toBe('3')
        ->type()->toBe('a')
        ->title()->toBe('b');
});

it('extract actor chat info', function () {
    $dto = Reaction::fromArray([
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
        'date' => 1727211008,
        'user' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
        'message_id' => 2,
        'new_reaction' => [
            [
                'type' => 'emoji',
                'emoji' => '👍',
            ],
        ],
        'old_reaction' => [],
    ]);

    expect($dto->actorChat())
        ->toBeInstanceOf(Chat::class)
        ->id()->toBe('3')
        ->type()->toBe('a')
        ->title()->toBe('b');
});

it('extract from info', function () {
    $dto = Reaction::fromArray([
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
        'date' => 1727211008,
        'user' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
        'message_id' => 2,
        'new_reaction' => [
            [
                'type' => 'emoji',
                'emoji' => '👍',
            ],
        ],
        'old_reaction' => [],
    ]);

    expect($dto->from())
        ->toBeInstanceOf(User::class)
        ->id()->toBe(1)
        ->firstName()->toBe('a')
        ->lastName()->toBe('b');
});

it('extract old_reaction info', function () {
    $dto = Reaction::fromArray([
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
        'date' => 1727211008,
        'user' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
        'message_id' => 2,
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
    ]);

    expect($dto->oldReaction()->toArray())->toBe([
        [
            'type' => 'emoji',
            'emoji' => '🔥',
        ],
    ]);
});

it('extract new_reaction info', function () {
    $dto = Reaction::fromArray([
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
        'date' => 1727211008,
        'user' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
        'message_id' => 2,
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
    ]);

    expect($dto->newReaction()->toArray())->toBe([
        [
            'type' => 'emoji',
            'emoji' => '👍',
        ],
    ]);
});

it('only custom reaction', function () {
    $dto = Reaction::fromArray([
        'chat' => [
            'id' => 3,
            'type' => 'a',
            'title' => 'b',
        ],
        'date' => 1727211008,
        'user' => [
            'id' => 1,
            'is_bot' => false,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
        ],
        'message_id' => 2,
        'new_reaction' => [
            [
                'type' => 'emoji',
                'custom_emoji_id' => '123',
            ],
        ],
        'old_reaction' => [
            [
                'type' => 'emoji',
                'custom_emoji_id' => '456',
            ],
        ],
    ]);

    expect($dto->newReaction()->toArray())->toBe([
        [
            'type' => 'emoji',
            'custom_emoji_id' => '123',
        ],
    ]);
});
