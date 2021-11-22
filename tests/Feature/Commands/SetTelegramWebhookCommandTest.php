<?php


use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use function Pest\Laravel\artisan;

uses(LazilyRefreshDatabase::class);

test('can set telegram webhook address if there is only one', function () {
    bot();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook')
        ->expectsOutput("Webhook updated")
        ->assertSuccessful();
});

test('it requires bot id if there are more than one', function () {
    bots(2);

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook')
        ->expectsOutput("Please specify a Bot ID")
        ->assertFailed();
});

test('can set telegram webhook address for a bot if given its ID', function () {
    $bot = bots(2)->first();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan("telegraph:set-webhook $bot->id")
        ->expectsOutput("Webhook updated")
        ->assertSuccessful();
});
