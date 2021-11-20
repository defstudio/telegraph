<?php

namespace DefStudio\LaravelTelegraph\Tests;

use DefStudio\LaravelTelegraph\LaravelTelegraphServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelTelegraphServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
