<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\DTO\PreCheckoutQuery;
use DefStudio\Telegraph\DTO\SuccessfulPayment;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = SuccessfulPayment::fromArray([
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
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
