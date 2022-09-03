<?php

use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

test('button widths are computed', function (int $buttons, string $keyboardClass) {
    /** @var Keyboard|ReplyKeyboard $keyboard */
    /** @noinspection PhpUndefinedMethodInspection */
    $keyboard = $keyboardClass::make();

    foreach (range(1, $buttons) as $buttonIndex) {
        $keyboard = $keyboard->button($buttonIndex)
            ->width(1 / $buttons);
    }

    expect($keyboard->toArray()[0])
        ->toHaveCount($buttons);
})->with(range(1, 20))
    ->with([Keyboard::class, ReplyKeyboard::class]);
