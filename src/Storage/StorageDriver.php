<?php

namespace DefStudio\Telegraph\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class StorageDriver implements \DefStudio\Telegraph\Contracts\StorageDriver
{
    private const MODEL_CLASS_KEY = ':tgph_model_class:';
    private const MODEL_ID_KEY = ':tgph_model_id:';

    final public function set(string $key, mixed $value): static
    {
        $this->storeData($key, $this->dehydrate($value));

        return $this;
    }

    final public function get(string $key, mixed $default = null): mixed
    {
        return $this->hydrate($this->retrieveData($key, $default));
    }

    abstract protected function storeData(string $key, mixed $value): void;

    abstract protected function retrieveData(string $key, mixed $default): mixed;

    private function dehydrate(mixed $value): mixed
    {
        if (is_iterable($value)) {
            /** @var iterable<array-key, mixed> $value */
            return Collection::make($value)
                ->map(fn ($item) => $this->dehydrate($item))
                ->toArray();
        }

        if ($value instanceof Model) {
            return [
                self::MODEL_CLASS_KEY => $value::class,
                self::MODEL_ID_KEY => $value->getKey(),
            ];
        }

        return $value;
    }

    private function hydrate(mixed $value): mixed
    {
        if (is_iterable($value)) {
            /** @var iterable<array-key, mixed> $value */
            $collection = Collection::make($value);
            if ($collection->has(self::MODEL_CLASS_KEY)) {
                /** @var class-string<Model> $modelClass */
                $modelClass = $collection->get(self::MODEL_CLASS_KEY);

                return $modelClass::find($collection->get(self::MODEL_ID_KEY));
            }

            return $collection->map(fn ($item) => $this->hydrate($item))->toArray();
        }

        return $value;
    }
}
