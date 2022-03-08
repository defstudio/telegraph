<?php

use DefStudio\Telegraph\Telegraph;

it('can return the telegram request url', function () {
    $url = app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->getUrl();

    expect($url)->toMatchSnapshot();
});

it('can dump the request to an array', function () {
    $array = app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->toArray();

    expect($array)->toMatchSnapshot();
});
