<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Storage;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CacheStorageDriver extends StorageDriver
{
    private string $key;
    private Repository $cache;

    /**
     * @param string[] $configuration
     */
    public function __construct(string $itemClass, string $itemKey, array $configuration)
    {
        $this->key = (string) Str::of($configuration['key_prefix'])
            ->append("_", class_basename($itemClass))
            ->append("_", $itemKey);

        $this->cache = Cache::store($configuration['store'] ?? null);
    }

    public function storeData(string $key, mixed $value): void
    {
        $this->cache->set("{$this->key}_$key", $value);
    }

    public function retrieveData(string $key, mixed $default = null): mixed
    {
        return $this->cache->get("{$this->key}_$key", $default);
    }

    public function forget(string $key): static
    {
        $this->cache->forget("{$this->key}_$key");

        return $this;
    }
}
