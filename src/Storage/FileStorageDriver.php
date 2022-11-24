<?php

namespace DefStudio\Telegraph\Storage;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JsonException;

class FileStorageDriver extends StorageDriver
{
    private string $file;

    private Filesystem $disk;

    /**
     * @param string[] $configuration
     */
    public function __construct(string $itemClass, string $itemKey, array $configuration)
    {
        $this->file = (string) Str::of($configuration['root'])
            ->append('/', class_basename($itemClass))
            ->append("/", $itemKey, '.json');

        $this->disk = Storage::disk($configuration['disk'] ?? null);
    }

    /**
     * @return array<array-key, mixed>
     */
    private function getDataFromJson(): array
    {
        try {
            $json = $this->disk->get($this->file);

            /** @phpstan-ignore-next-line */
            return rescue(fn () => json_decode($json, true, flags: JSON_THROW_ON_ERROR), []);
        } catch (JsonException|FileNotFoundException) {
            return [];
        }
    }

    /**
     * @param array<array-key, mixed> $data
     */
    private function storeDataInJson(array $data): void
    {
        $json = json_encode($data);

        /** @phpstan-ignore-next-line */
        $this->disk->put($this->file, $json);
    }

    public function storeData(string $key, mixed $value): void
    {
        $data = $this->getDataFromJson();
        Arr::set($data, $key, $value);
        $this->storeDataInJson($data);
    }

    public function retrieveData(string $key, mixed $default = null): mixed
    {
        $data = $this->getDataFromJson();

        return Arr::get($data, $key, $default);
    }

    public function forget(string $key): static
    {
        $data = $this->getDataFromJson();
        Arr::forget($data, $key);
        $this->storeDataInJson($data);

        return $this;
    }
}
