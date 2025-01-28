<?php

namespace DefStudio\Telegraph\Contracts;

interface Storable
{
    public function storage(string|null $driver = null): StorageDriver;

    public function storageKey(): string|int;
}
