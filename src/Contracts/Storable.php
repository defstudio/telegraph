<?php

namespace DefStudio\Telegraph\Contracts;

interface Storable
{
    public function storage(string $driver = null): StorageDriver;

    public function storageKey(): string|int;
}
