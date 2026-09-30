<?php

use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Tests\Unit\Storage\TestStorable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
    Storage::fake();
});

function testStorableStorageFile(TestStorable $class): string
{
    return (string) Str::of('telegraph')
        ->append('/', class_basename($class::class))
        ->append("/", 'foo', '.json');
}

it('can store and retrieve data', function (string $key, mixed $value) {
    $class = new TestStorable();

    $class->storage('file')->set($key, $value);

    $file = testStorableStorageFile($class);

    expect(Storage::exists($file))->toBeTrue();

    $data = json_decode(Storage::get($file), true);
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

it('can store and retrieve a model', function () {
    $class = new TestStorable();

    $model = TelegraphChat::factory()->create();

    $class->storage('file')->set('model', $model);

    $file = testStorableStorageFile($class);

    expect(Storage::exists($file))->toBeTrue();

    $data = json_decode(Storage::get($file), true);

    expect(data_get($data, 'model'))->toBe([
        ':tgph_model_class:' => TelegraphChat::class,
        ':tgph_model_id:' => $model->id,
    ]);

    expect($class->storage('file')->get('model'))
        ->toBeInstanceOf(TelegraphChat::class)
        ->id->toBe($model->id);
});

it('can store and retrieve a nested model', function () {
    $class = new TestStorable();

    $models = TelegraphChat::factory()->count(4)->create();

    $class->storage('file')->set('models', $models);

    $file = testStorableStorageFile($class);

    expect(Storage::exists($file))->toBeTrue();

    $data = json_decode(Storage::get($file), true);

    expect(data_get($data, 'models'))->toBe([
        [
            ':tgph_model_class:' => TelegraphChat::class,
            ':tgph_model_id:' => 1,
        ],
        [
            ':tgph_model_class:' => TelegraphChat::class,
            ':tgph_model_id:' => 2,
        ],
        [
            ':tgph_model_class:' => TelegraphChat::class,
            ':tgph_model_id:' => 3,
        ],
        [
            ':tgph_model_class:' => TelegraphChat::class,
            ':tgph_model_id:' => 4,
        ],
    ]);

    expect($class->storage('file')->get('models.0'))->toBeInstanceOf(TelegraphChat::class)->id->toBe(1);
    expect($class->storage('file')->get('models.1'))->toBeInstanceOf(TelegraphChat::class)->id->toBe(2);
    expect($class->storage('file')->get('models.2'))->toBeInstanceOf(TelegraphChat::class)->id->toBe(3);
    expect($class->storage('file')->get('models.3'))->toBeInstanceOf(TelegraphChat::class)->id->toBe(4);
});

it('falls back to the default value when the file storage data is missing or empty', function (?string $contents) {
    $class = new TestStorable();
    $file = testStorableStorageFile($class);

    if ($contents !== null) {
        Storage::put($file, $contents);
    }

    expect($class->storage('file')->get('missing', 'fallback'))->toBe('fallback')
        ->and($class->storage('file')->get('missing'))->toBeNull();
})->with([
    'missing file' => [null],
    'empty file' => [''],
    'blank file' => [" \n\t "],
]);

it('falls back safely when the file storage data is invalid json', function () {
    $class = new TestStorable();
    $file = testStorableStorageFile($class);

    Storage::put($file, '{invalid json');

    expect($class->storage('file')->get('missing', 'fallback'))->toBe('fallback');
});

it('overwrites empty or invalid file storage data with valid json', function (string $contents) {
    $class = new TestStorable();
    $file = testStorableStorageFile($class);

    Storage::put($file, $contents);

    $class->storage('file')->set('foo.bar', 'baz');

    expect(json_decode(Storage::get($file), true, flags: JSON_THROW_ON_ERROR))
        ->toBe(['foo' => ['bar' => 'baz']]);
})->with([
    'empty file' => [''],
    'blank file' => [" \n\t "],
    'invalid json' => ['{invalid json'],
]);
