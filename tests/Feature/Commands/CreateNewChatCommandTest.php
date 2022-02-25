<?php

use Symfony\Component\Console\Command\Command;
use function Pest\Laravel\artisan;

it('bot id is required if there are more than one bot', function () {
    bot('AAAAA');
    bot('BBBBB');

    artisan("telegraph:new-chat")
        ->expectsOutput("Please specify a Bot ID")
        ->assertFailed();
});

it('can create a chat for the default bot', function () {
    $bot = bot();

    artisan("telegraph:new-chat")
        ->expectsOutput("You are about to create a new Telegram Chat for bot $bot->name")
        ->expectsQuestion("Enter the chat id - press [x] to abort:", '123456')
        ->expectsQuestion("Enter the chat name (optional):", 'Test Chat')
        ->assertExitCode(Command::SUCCESS);
});

it('requires a chat id', function () {
    $bot = bot();

    artisan("telegraph:new-chat")
        ->expectsOutput("You are about to create a new Telegram Chat for bot $bot->name")
        ->expectsQuestion("Enter the chat id - press [x] to abort:", '')
        ->expectsOutput("The chat ID cannot be null")
        ->expectsQuestion("Enter the chat id - press [x] to abort:", '123456')
        ->expectsQuestion("Enter the chat name (optional):", 'Test Chat')
        ->assertExitCode(Command::SUCCESS);
});
