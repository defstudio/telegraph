<?php

use DefStudio\Telegraph\Concerns\HasStorage;
use DefStudio\Telegraph\Contracts\Storable;
use DefStudio\Telegraph\Exceptions\StorageException;
use DefStudio\Telegraph\Storage\FileStorageDriver;

it('can return default store driver', function () {
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    expect($class->storage())->toBeInstanceOf(FileStorageDriver::class);
});

it('can return custom store drivers', function (string $store) {
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    expect($class->storage($store))->toBeInstanceOf(config("telegraph.storage.stores.$store.driver"));
})->with([
    'file',
    'cache',
]);

it('requires a default store', function () {
    config()->set('telegraph.storage.default');
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    $class->storage();
})->throws(StorageException::class, 'No default driver defined in telegraph.storage.default config');


it('requires a driver configuration to exist', function () {
    config()->set('telegraph.storage.stores.file');
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    $class->storage();
})->throws(StorageException::class, 'No [file] driver configuration defined telegraph.storage.stores.file config');
