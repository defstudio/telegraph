<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\PreCheckoutQuery;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = PreCheckoutQuery::fromArray([
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
        ],
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
