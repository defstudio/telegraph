<?php

namespace DefStudio\Telegraph\Tests;

use DefStudio\Telegraph\TelegraphServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            TelegraphServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
