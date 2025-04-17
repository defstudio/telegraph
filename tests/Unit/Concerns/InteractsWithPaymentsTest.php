<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */

use DefStudio\Telegraph\Telegraph;

it('can refund star payment for a user', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->refundStarPayment(123456, "payment_charge_id"))
        ->toMatchTelegramSnapshot();
});
