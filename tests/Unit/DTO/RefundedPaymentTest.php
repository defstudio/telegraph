<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RefundedPayment;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RefundedPayment::fromArray([
        'currency' => 'XTR',
        'total_amount' => 100,
        'invoice_payload' => 'id_10',
        'telegram_payment_charge_id' => 10,
        'provider_payment_charge_id' => 10,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
