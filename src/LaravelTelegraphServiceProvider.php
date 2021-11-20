<?php

namespace DefStudio\LaravelTelegraph;

use DefStudio\LaravelTelegraph\Commands\GetTelegramWebhookDebugInfoCommand;
use DefStudio\LaravelTelegraph\Commands\SetTelegramWebhookCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTelegraphServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-telegraph')
            ->hasConfigFile()
            ->hasRoute('api')
            ->hasCommand(SetTelegramWebhookCommand::class)
            ->hasCommand(GetTelegramWebhookDebugInfoCommand::class);

        $this->app->bind('laravel-telegraph', fn() => new LaravelTelegraph());
    }
}
