<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\ChatInviteLink;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = ChatInviteLink::fromArray([
        'invite_link' => 'https:/t.me/+EEEEEEE...',
        'creator' => [
            'id' => 1,
            'is_bot' => true,
            'first_name' => 'a',
            'last_name' => 'b',
            'username' => 'c',
            'language_code' => 'd',
            'is_premium' => false,
        ],
        'name' => 'name',
        'expire_date' => now()->timestamp,
        'member_limit' => 1,
        'pending_join_requests_count' => 2,
        'subscription_period' => 3,
        'subscription_price' => 4,
        'creates_join_request' => true,
        'is_primary' => false,
        'is_revoked' => false,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
