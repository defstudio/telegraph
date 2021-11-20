<?php

use DefStudio\LaravelTelegraph\Facades\LaravelTelegraph as Telegraph;
use DefStudio\LaravelTelegraph\LaravelTelegraph;
use DefStudio\LaravelTelegraph\Support\Testing\Fakes\LaravelTelegraphFake;

test('Telegraph facade is registered', function () {
    expect(Telegraph::bot('1'))->toBeInstanceOf(LaravelTelegraph::class);
});

test('can switch to fake', function () {
    Telegraph::fake();
    expect(Telegraph::markdown('foo'))->toBeInstanceOf(LaravelTelegraphFake::class);
});
