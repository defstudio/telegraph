<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Giveaway;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = Giveaway::fromArray([
        'chats' => [
            [
                'id' => 1,
                'type' => 'channel',
                'title' => 'chat1',
                'username' => 'username1',
            ],
            [
                'id' => 2,
                'type' => 'channel',
                'title' => 'chat2',
                'username' => 'username2',
            ],
        ],
        'winners_selection_date' => 1001,
        'winner_count' => 1,
        'only_new_members' => true,
        'has_public_winners' => true,
        'prize_description' => 'prize',
        'country_codes' => [
            'AM',
            'BY',
        ],
        'prize_star_count' => 10,
        'premium_subscription_month_count' => 1,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
