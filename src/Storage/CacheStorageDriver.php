<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Storage;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
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
        if (!Str::of($key)->contains('.')) {
            $this->cache->set("{$this->key}_$key", $value);

            return;
        }

        $mainKey = (string)Str::of($key)->before('.');
        $mainValue = $this->retrieveData($mainKey, []);

        $otherKeys = (string)Str::of($key)->after('.');
        data_set($mainValue, $otherKeys, $value);

        $this->cache->set("{$this->key}_".$mainKey, $mainValue);
    }

    public function retrieveData(string $key, mixed $default = null): mixed
    {
        if (!Str::of($key)->contains('.')) {
            return $this->cache->get("{$this->key}_$key", $default);
        }

        $mainKey = (string) Str::of($key)->before('.');
        $mainValue = $this->retrieveData($mainKey, []);

        $otherKeys = (string) Str::of($key)->after('.');

        return data_get($mainValue, $otherKeys, $default);
    }

    public function forget(string $key): static
    {
        if (!Str::of($key)->contains('.')) {
            $this->cache->forget("{$this->key}_$key");
        }

        $mainKey = (string) Str::of($key)->before('.');
        $mainValue = $this->retrieveData($mainKey, []);

        $otherKeys = (string) Str::of($key)->after('.');

        Arr::forget($mainValue, $otherKeys);

        $this->storeData($mainKey, $mainValue);

        return $this;
    }
}
