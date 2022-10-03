<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Carbon;

it('can create a poll', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph
        ->poll('foo?')
        ->option('bar')
        ->option('baz')
    )->toMatchTelegramSnapshot();
});

it('can allow multiple answers', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph
        ->poll('foo?')
        ->option('bar')
        ->option('baz')
        ->allowMultipleAnswers()
    )->toMatchTelegramSnapshot();
});

it('can disable anonymous answers', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph
        ->poll('foo?')
        ->option('bar')
        ->option('baz')
        ->disableAnonymous()
    )->toMatchTelegramSnapshot();
});

it('can set poll validity', function () {
    \Spatie\PestPluginTestTime\testTime()->freeze(Carbon::make('2020-10-03'));
    expect(
        fn (Telegraph $telegraph) => $telegraph
        ->poll('foo?')
        ->option('bar')
        ->option('baz')
        ->validUntil(now()->addSeconds(30))
    )->toMatchTelegramSnapshot();
});
