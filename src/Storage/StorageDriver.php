<?php

namespace DefStudio\Telegraph\Storage;

use Illuminate\Database\Eloquent\Model;

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

    private function hydrate(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($item) => $this->hydrate($item), $value);
        }

        if ($value instanceof Model) {
            return [
                self::MODEL_CLASS_KEY => $value::class,
                self::MODEL_ID_KEY => $value->id,
            ];
        }

        return $value;
    }

    private function dehydrate(mixed $value): mixed
    {
        if (is_array($value)) {
            if (array_key_exists(self::MODEL_CLASS_KEY, $value)) {
                $modelClass = $value[self::MODEL_CLASS_KEY];
                return $modelClass::find($value[self::MODEL_ID_KEY]);
            }

            return array_map(fn ($item) => $this->dehydrate($item), $value);
        }

        return $value;
    }
}
