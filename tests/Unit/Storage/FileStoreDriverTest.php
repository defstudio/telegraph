<?php

use DefStudio\Telegraph\Concerns\HasStorage;
use DefStudio\Telegraph\Contracts\Storable;
use Illuminate\Support\Str;

beforeEach(function () {
    Storage::fake();
});

it('can store and retrieve data data', function (string $key, mixed $value) {
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    $class->storage('file')->set($key, $value);

    $file = Str::of('telegraph')
        ->append('/', class_basename($class::class))
        ->append("/", 'foo', '.json');

    expect(Storage::exists($file))->toBeTrue();

    $data = json_decode(Storage::get($file));
    expect(data_get($data, $key))->toBe($value);

    expect($class->storage('file')->get($key))->toBe($value);

    $class->storage('file')->forget($key);
    expect($class->storage('file')->get($key))->toBeNull();
})->with([
    'string' => ['key' => 'foo.bar', 'value' => 'baz'],
    'int' => ['key' => 'foo.bar', 'value' => 1],
    'float' => ['key' => 'foo.bar', 'value' => 1.994],
    'bool' => ['key' => 'foo.bar', 'value' => true],
    'array' => ['key' => 'foo.bar', 'value' => ['baz', 'qux']],
]);
