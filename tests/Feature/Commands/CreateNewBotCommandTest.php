<?php

use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Telegraph;
use function Pest\Laravel\artisan;

test('bot token is required', function () {
    artisan('telegraph:new-bot')
        ->expectsOutput('You are about to create a new Telegram Bot')
        ->expectsQuestion("Please, enter the bot token", "")
        ->expectsOutput('Token cannot be empty')
        ->assertFailed();
});

it('can create a new bot', function () {
    artisan('telegraph:new-bot')
        ->expectsOutput('You are about to create a new Telegram Bot')
        ->expectsQuestion("Please, enter the bot token", "123456789")
        ->expectsQuestion("Enter the bot name (optional)", "foo")
        ->expectsQuestion("Do you want to add a chat to this bot?", false)
        ->expectsQuestion("Do you want to setup a webhook for this bot?", false)
        ->assertSuccessful();


    expect(TelegraphBot::first())
        ->not->toBeNull()
        ->token->toBe('123456789')
        ->name->toBe('foo');
});

it('assigns a default name if not provided', function () {
    artisan('telegraph:new-bot')
        ->expectsOutput('You are about to create a new Telegram Bot')
        ->expectsQuestion("Please, enter the bot token", "123456789")
        ->expectsQuestion("Enter the bot name (optional)", "")
        ->expectsQuestion("Do you want to add a chat to this bot?", false)
        ->expectsQuestion("Do you want to setup a webhook for this bot?", false)
        ->assertSuccessful();


    expect(TelegraphBot::first())
        ->not->toBeNull()
        ->token->toBe('123456789')
        ->name->toBe('Bot #1');
});

it('can assign a chat to the new bot', function () {
    artisan('telegraph:new-bot')
        ->expectsOutput('You are about to create a new Telegram Bot')
        ->expectsQuestion("Please, enter the bot token", "123456789")
        ->expectsQuestion("Enter the bot name (optional)", "foo")
        ->expectsQuestion("Do you want to add a chat to this bot?", true)
        ->expectsQuestion("Enter the chat id - press [x] to abort:", "888999444")
        ->expectsQuestion("Enter the chat name (optional):", 'bar')
        ->expectsQuestion("Do you want to setup a webhook for this bot?", false)
        ->assertSuccessful();


    /** @var TelegraphBot|null $bot */
    $bot = TelegraphBot::first();

    expect($bot)
        ->not->toBeNull()
        ->token->toBe('123456789')
        ->name->toBe('foo');

    expect($bot->chats()->first())
        ->chat_id->toBe('888999444');
});

it('keeps asking for the chat name until pressed x', function () {
    artisan('telegraph:new-bot')
        ->expectsOutput('You are about to create a new Telegram Bot')
        ->expectsQuestion("Please, enter the bot token", "123456789")
        ->expectsQuestion("Enter the bot name (optional)", "foo")
        ->expectsQuestion("Do you want to add a chat to this bot?", true)
        ->expectsQuestion("Enter the chat id - press [x] to abort:", "")
        ->expectsQuestion("Enter the chat id - press [x] to abort:", "")
        ->expectsQuestion("Enter the chat id - press [x] to abort:", "x")
        ->expectsQuestion("Do you want to setup a webhook for this bot?", false)
        ->assertSuccessful();


    /** @var TelegraphBot|null $bot */
    $bot = TelegraphBot::first();

    expect($bot)
        ->not->toBeNull()
        ->token->toBe('123456789')
        ->name->toBe('foo');

    expect($bot->chats()->count())->toBe(0);
});

it('can register the new bot webhook', function () {
    Facade::fake([
        Telegraph::ENDPOINT_SET_WEBHOOK => [
            'ok' => true,
        ],
    ]);

    artisan('telegraph:new-bot')
        ->expectsOutput('You are about to create a new Telegram Bot')
        ->expectsQuestion("Please, enter the bot token", "123456789")
        ->expectsQuestion("Enter the bot name (optional)", "")
        ->expectsQuestion("Do you want to add a chat to this bot?", false)
        ->expectsQuestion("Do you want to setup a webhook for this bot?", true)
        ->assertSuccessful();


    expect(TelegraphBot::first())
        ->not->toBeNull()
        ->token->toBe('123456789')
        ->name->toBe('Bot #1');

    Facade::assertRegisteredWebhook();
});
