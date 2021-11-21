<?php


use DefStudio\Telegraph\Facades\Telegraph;
use function Pest\Laravel\artisan;

test('can set telegram webhook address', function () {
    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook')
        ->expectsOutput("Webhook updated")
        ->assertSuccessful();
});
