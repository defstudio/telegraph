<?php


use DefStudio\LaravelTelegraph\Facades\LaravelTelegraph;
use function Pest\Laravel\artisan;

test('can set telegram webhook address', function () {
    LaravelTelegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook')
        ->expectsOutput("Webhook updated")
        ->assertSuccessful();
});
