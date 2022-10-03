<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Carbon;

it('can create a quiz', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph
        ->quiz('foo?')
        ->option('bar')
        ->option('baz', true)
        ->option('qux')
    )->toMatchTelegramSnapshot();
});


it('can disable anonymous answers', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph
        ->quiz('foo?')
        ->option('bar')
        ->option('baz')
        ->disableAnonymous()
    )->toMatchTelegramSnapshot();
});

it('can set quiz validity', function () {
    \Spatie\PestPluginTestTime\testTime()->freeze(Carbon::make('2020-10-03'));
    expect(
        fn (Telegraph $telegraph) => $telegraph
        ->quiz('foo?')
        ->option('bar')
        ->option('baz')
        ->validUntil(now()->addSeconds(30))
    )->toMatchTelegramSnapshot();
});
