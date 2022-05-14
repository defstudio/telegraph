<?php

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\Commands\CreateNewBotCommand;
use DefStudio\Telegraph\Commands\CreateNewChatCommand;
use DefStudio\Telegraph\Commands\GetTelegramWebhookDebugInfoCommand;
use DefStudio\Telegraph\Commands\SetTelegramWebhookCommand;
use DefStudio\Telegraph\Commands\UnsetTelegramWebhookCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TelegraphServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('telegraph')
            ->hasConfigFile()
            ->hasRoute('api')
            ->hasMigration('create_telegraph_bots_table')
            ->hasMigration('create_telegraph_chats_table')
            ->hasCommand(CreateNewBotCommand::class)
            ->hasCommand(CreateNewChatCommand::class)
            ->hasCommand(SetTelegramWebhookCommand::class)
            ->hasCommand(UnsetTelegramWebhookCommand::class)
            ->hasCommand(GetTelegramWebhookDebugInfoCommand::class);

        $this->app->bind('telegraph', fn () => new Telegraph());
    }
}
