<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichText\RichTextString;
use DefStudio\Telegraph\Exceptions\RichTextException;

it('export all properties', function () {
    $dto = RichTextString::fromData('test');

    $string = $dto->build();

    expect($string)->toBe('test');
});

it('throw exception with wrong data structure', function () {
    expect(fn () => RichTextString::fromData(['test']))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});
