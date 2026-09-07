<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockDivider;
use DefStudio\Telegraph\Exceptions\RichBlockException;

it('export all properties to array', function () {
    $dto = RichBlockDivider::fromArray([
        'type' => 'divider',
    ]);

    $array = $dto->toArray();

    expect($array['type'])->toBe($dto->type());
});

it('throw exception with wrong type', function () {
    expect(fn () => RichBlockDivider::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
