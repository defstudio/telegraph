<?php

use DefStudio\Telegraph\Concerns\HasStorage;
use DefStudio\Telegraph\Contracts\Storable;
use Illuminate\Support\Str;

it('can store and retrieve data data', function (string $key, mixed $value) {
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    config()->set('cache.default', 'file');
    $class->storage('cache')->set($key, $value);

    $cacheKey = Str::of('tgph')
        ->append("_", class_basename($class::class))
        ->append("_", 'foo');

    expect(Cache::get("{$cacheKey}_$key"))->toBe($value);
    expect($class->storage('cache')->get($key))->toBe($value);

    $class->storage('cache')->forget($key);
    expect($class->storage('cache')->get($key))->toBeNull();
})->with([
    'string' => ['key' => 'foo.bar', 'value' => 'baz'],
    'int' => ['key' => 'foo.bar', 'value' => 1],
    'float' => ['key' => 'foo.bar', 'value' => 1.994],
    'bool' => ['key' => 'foo.bar', 'value' => true],
    'array' => ['key' => 'foo.bar', 'value' => ['baz', 'qux']],
]);
