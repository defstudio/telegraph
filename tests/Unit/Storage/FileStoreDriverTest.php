<?php

use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Tests\Unit\Storage\TestStorable;
use Illuminate\Support\Str;

beforeEach(function () {
    Storage::fake();
});

it('can store and retrieve data', function (string $key, mixed $value) {
    $class = new TestStorable();

    $class->storage('file')->set($key, $value);

    $file = Str::of('telegraph')
        ->append('/', class_basename($class::class))
        ->append("/", 'foo', '.json');

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

    $file = Str::of('telegraph')
        ->append('/', class_basename($class::class))
        ->append("/", 'foo', '.json');

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

    $file = Str::of('telegraph')
        ->append('/', class_basename($class::class))
        ->append("/", 'foo', '.json');

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
