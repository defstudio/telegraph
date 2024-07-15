<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\WriteAccessAllowed;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = WriteAccessAllowed::fromArray([
        "from_request" => true,
        "web_app_name" => "test",
        "from_attachment_menu" => true,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});
