<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichText\RichTextMathematicalExpression;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Str;

it('export all properties', function () {
    $dto = RichTextMathematicalExpression::fromData([
        'type' => 'mathematical_expression',
        'expression' => 'test',
    ]);

    $reflection = new ReflectionClass($dto);

    $array = $dto->build();

    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(
            Str::of($property->name)->snake()->toString()
        );
    }
});

it('throw exception with wrong data structure', function () {
    expect(fn () => RichTextMathematicalExpression::fromData('test'))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});

it('throw exception with wrong type', function () {
    expect(fn () => RichTextMathematicalExpression::fromData(['type' => 'test']))
        ->toThrow(RichTextException::structureMismatch(), 'The RichTextItem provided structure is not valid');
});
