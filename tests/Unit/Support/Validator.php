<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Support\Validator;
use DefStudio\Telegraph\Exceptions\ArgumentException;

test('menu button type required', function () {
    Validator::validateMenuButtonParameters([]);
})->throws(ArgumentException::class, "Missing parameter: type");

test('menu button text required on web_app type', function () {
    Validator::validateMenuButtonParameters(["type" => "web_app"]);
})->throws(ArgumentException::class, "Missing parameter: text");

test('menu button url required on web_app type', function () {
    Validator::validateMenuButtonParameters([
        "type" => "web_app",
        "text" => "VISIT",
    ]);
})->throws(ArgumentException::class, "Missing parameter: url");
