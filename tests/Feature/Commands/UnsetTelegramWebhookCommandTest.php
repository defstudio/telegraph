<?php


use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function Pest\Laravel\artisan;

use Symfony\Component\Console\Command\Command;

uses(LazilyRefreshDatabase::class);

test('can set telegram webhook address if there is only one', function () {
    bot();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan('telegraph:unset-webhook')
        ->expectsOutput("Webhook deleted")
        ->assertExitCode(Command::SUCCESS);
});

test('it requires bot id if there are more than one', function () {
    bots(2);

    /** @phpstan-ignore-next-line */
    artisan('telegraph:unset-webhook')
        ->expectsOutput("Please specify a Bot ID")
        ->assertExitCode(Command::FAILURE);
});

test('can set telegram webhook address for a bot if given its ID', function () {
    $bot = bots(2)->first();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan("telegraph:unset-webhook $bot->id")
        ->expectsOutput("Webhook deleted")
        ->assertExitCode(Command::SUCCESS);
});

test('it dumps error when telegram request is unsuccessful', function () {
    $bot = bots(2)->first();

    Telegraph::fake([
        \DefStudio\Telegraph\Telegraph::ENDPOINT_UNSET_WEBHOOK => [
            'ok' => false,
            'foo' => 'bar',
        ],
    ]);

    /** @phpstan-ignore-next-line */
    artisan("telegraph:unset-webhook $bot->id")
        ->expectsOutput("Failed to unregister webhook")
        ->expectsOutput('{"ok":false,"foo":"bar"}')
        ->assertExitCode(Command::FAILURE);
});
