<?php

namespace DefStudio\Telegraph\Tests;

use DefStudio\Telegraph\TelegraphServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'DefStudio\\Telegraph\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
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
        $this->filesystemSetup($app['config']);

        $app['config']->set('database.default', 'testing');

    }

    protected function defineDatabaseMigrations(): void
    {
        ;
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function filesystemSetup($config): void
    {
        $storagePath = __DIR__ . '/storage';

        $filesystem = new Filesystem();
        $filesystem->ensureDirectoryExists($storagePath);

        $config->set('filesystems.default', 'local');
        $config->set('filesystems.disks.local.driver', 'local');
        $config->set('filesystems.disks.local.root', realpath($storagePath));
    }
}
