<?php


use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function Pest\Laravel\artisan;

use Symfony\Component\Console\Command\Command;

uses(LazilyRefreshDatabase::class);

test('can set telegram webhook address if there is only one', function () {
    withfakeUrl();
    bot();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook')
        ->expectsOutput("Webhook updated")
        ->assertExitCode(Command::SUCCESS);
});

test('can set telegram webhook dropping pending updates', function () {
    withfakeUrl();
    bot();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook', ['--drop-pending-updates' => true])
        ->expectsOutput("Webhook updated")
        ->assertExitCode(Command::SUCCESS);

    Telegraph::assertRegisteredWebhook([
        'drop_pending_updates' => true,
    ], false);
});

test('can set telegram webhook settings its max connections', function () {
    withfakeUrl();
    bot();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook', ['--max-connections' => 99])
        ->expectsOutput("Webhook updated")
        ->assertExitCode(Command::SUCCESS);

    Telegraph::assertRegisteredWebhook([
        'max_connections' => 99,
    ], false);
});

test('can set telegram webhook settings its secret token', function () {
    withfakeUrl();
    bot();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook', ['--secret' => 'foo'])
        ->expectsOutput("Webhook updated")
        ->assertExitCode(Command::SUCCESS);

    Telegraph::assertRegisteredWebhook([
        'secret_token' => 'foo',
    ], false);
});

test('it requires bot id if there are more than one', function () {
    bots(2);

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook')
        ->expectsOutput("Please specify a Bot ID")
        ->assertExitCode(Command::FAILURE);
});

test('can set telegram webhook address for a bot if given its ID', function () {
    withfakeUrl();
    $bot = bots(2)->first();

    Telegraph::fake();

    /** @phpstan-ignore-next-line */
    artisan("telegraph:set-webhook $bot->id")
        ->expectsOutput("Webhook updated")
        ->assertExitCode(Command::SUCCESS);
});

test('it dumps error when telegram request is unsuccessful', function () {
    withfakeUrl();
    $bot = bots(2)->first();

    Telegraph::fake([
        \DefStudio\Telegraph\Telegraph::ENDPOINT_SET_WEBHOOK => [
            'ok' => false,
            'foo' => 'bar',
        ],
    ]);

    /** @phpstan-ignore-next-line */
    artisan("telegraph:set-webhook $bot->id")
        ->expectsOutput("Failed to register webhook")
        ->expectsOutput('{"ok":false,"foo":"bar"}')
        ->assertExitCode(Command::FAILURE);
});
