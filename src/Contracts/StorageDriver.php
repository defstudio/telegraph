<?php

namespace DefStudio\Telegraph\Contracts;

interface StorageDriver
{
    /**
     * @param string[] $configuration
     */
    public function __construct(string $itemClass, string $itemKey, array $configuration);

    public function set(string $key, mixed $value): static;

    public function get(string $key, mixed $default = null): mixed;

    public function forget(string $key): static;
}
