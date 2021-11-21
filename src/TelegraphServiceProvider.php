<?php

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\Commands\GetTelegramWebhookDebugInfoCommand;
use DefStudio\Telegraph\Commands\SetTelegramWebhookCommand;
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
            ->hasCommand(SetTelegramWebhookCommand::class)
            ->hasCommand(GetTelegramWebhookDebugInfoCommand::class);

        $this->app->bind('telegraph', fn () => new Telegraph());
    }
}
