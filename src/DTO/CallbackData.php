<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;

abstract class CallbackData implements Arrayable
{
    protected static string $name;

    public function __construct(array|string $callbackData = [])
    {
        $decoded = $callbackData;
        is_array($decoded) || $decoded = $this->parseString($callbackData);

        $class = new ReflectionClass(static::class);
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $this->{$property->getName()} = Arr::get($decoded, $property->getName());
        }
    }

    public function toArray(): array
    {
        $class = new ReflectionClass(static::class);

        return Collection::wrap($class->getProperties(ReflectionProperty::IS_PUBLIC))
            ->mapWithKeys(function (ReflectionProperty $property): array {
                return [$property->getName() => $property->getValue($this)];
            })
            ->put('action', static::name())
            ->all();
    }

    // callback_data format: `action?param=1&...`
    public function __toString(): string
    {
        return mb_convert_encoding(
            sprintf("%s?%s", static::name(), http_build_query(Arr::except($this->toArray(), 'action'))),
            "ASCII"
        );
    }

    public static function name(): string
    {
        return static::$name ?? static::name_();
    }

    protected static function name_(): string
    {
        return (string)Str::of(static::class)->afterLast('\\')->lower();
    }

    private function parseString(string $callbackData): array
    {
        $decoded = [];
        parse_str($callbackData, $decoded);

        return $decoded;
    }
}
