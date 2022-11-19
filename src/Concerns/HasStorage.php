<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Contracts\StorageDriver;
use DefStudio\Telegraph\Exceptions\StorageException;

trait HasStorage
{
    public function storage(string $driver = null): StorageDriver
    {
        $driver ??= config('telegraph.storage.default');

        /** @var string|null $driver */
        if ($driver === null) {
            throw StorageException::noDefaultDriver();
        }


        $config = config("telegraph.storage.stores.$driver");


        if ($config === null) {
            throw StorageException::driverNotFound($driver);
        }

        /** @phpstan-ignore-next-line  */
        return app()->make($config['driver'], ['itemClass' => static::class, 'itemKey' => $this->storageKey(), 'configuration' => $config]);
    }
}
