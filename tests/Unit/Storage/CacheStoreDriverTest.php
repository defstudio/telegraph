<?php

use DefStudio\Telegraph\Concerns\HasStorage;
use DefStudio\Telegraph\Contracts\Storable;
use DefStudio\Telegraph\Models\TelegraphChat;

it('can store and retrieve data', function (string $key, mixed $value) {
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    $class->storage('cache')->set($key, $value);

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


it('can store and retrieve a model', function () {
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    $model = TelegraphChat::factory()->create();

    $class->storage('cache')->set('model', $model);

    expect($class->storage('cache')->get('model'))
        ->toBeInstanceOf(TelegraphChat::class)
        ->id->toBe($model->id);
});

it('can store and retrieve a nested model', function () {
    $class = new class () implements Storable {
        use HasStorage;

        public function storageKey(): string
        {
            return 'foo';
        }
    };

    $models = TelegraphChat::factory()->count(4)->create();

    $class->storage('cache')->set('models', $models);


    expect($class->storage('cache'))
        ->get('models.0')->id->toBe(1)
        ->get('models.1')->id->toBe(2)
        ->get('models.2')->id->toBe(3)
        ->get('models.3')->id->toBe(4);
});
