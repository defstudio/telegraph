<?php

namespace DefStudio\Telegraph\Storage;

use DefStudio\Telegraph\Contracts\StorageDriver;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageDriver implements StorageDriver
{
    private string $file;

    private Filesystem $disk;

    /**
     * @param string[] $configuration
     */
    public function __construct(string $itemClass, string $itemKey, array $configuration)
    {
        $this->file = Str::of($configuration['root'])
            ->append('/', class_basename($itemClass))
            ->append("/", $itemKey, '.json')
            ->toString();
        $this->disk = Storage::disk($configuration['disk'] ?? null);
    }

    /**
     * @return array<array-key, mixed>
     */
    private function getData(): array
    {
        $json = $this->disk->get($this->file) ?? '{}';

        /** @phpstan-ignore-next-line  */
        return json_decode($json, true);
    }

    /**
     * @param array<array-key, mixed> $data
     */
    private function storeData(array $data): void
    {
        $json = json_encode($data);

        /** @phpstan-ignore-next-line  */
        $this->disk->put($this->file, $json);
    }

    public function set(string $key, mixed $value): static
    {
        $data = $this->getData();
        Arr::set($data, $key, $value);
        $this->storeData($data);

        return $this;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $data = $this->getData();

        return Arr::get($data, $key, $default);
    }

    public function forget(string $key): static
    {
        $data = $this->getData();
        Arr::forget($data, $key);
        $this->storeData($data);

        return $this;
    }
}
