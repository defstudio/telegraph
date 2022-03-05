<?php

use DefStudio\Telegraph\Telegraph;

it('can send an html message', function () {
    expect(function (Telegraph $telegraph) {
        $telegraph->html('foobar');
    })->toMatchUrlSnapshot();
});

it('can send a markdown message', function () {
    expect(function (Telegraph $telegraph) {
        $telegraph->markdown('foobar');
    })->toMatchUrlSnapshot();
});

it('can send protected content', function () {
    expect(function (Telegraph $telegraph) {
        $telegraph->markdown('test')->protected();
    })->toMatchUrlSnapshot();
});

it('can send silent messages', function () {
    expect(function (Telegraph $telegraph) {
        $telegraph->markdown('test')->silent();
    })->toMatchUrlSnapshot();
});

it('can disable url preview', function () {
    expect(function (Telegraph $telegraph) {
        $telegraph->markdown('test')->withoutPreview();
    })->toMatchUrlSnapshot();
});
