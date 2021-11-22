<?php

namespace DefStudio\Telegraph\Tests;

use DefStudio\Telegraph\TelegraphServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'DefStudio\\Telegraph\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            TelegraphServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_telegraph_bots_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_telegraph_chats_table.php.stub';
        $migration->up();
    }
}
