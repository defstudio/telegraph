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

test('4-4-1-4 buttons', function () {
    $keyboard = Keyboard::make()
        ->button(1)->width(0.25)
        ->button(2)->width(0.25)
        ->button(3)->width(0.25)
        ->button(4)->width(0.25)
        ->button(5)->width(1)
        ->button(6)->width(0.25)
        ->button(7)->width(0.25)
        ->button(8)->width(0.25)
        ->button(9)->width(0.25)
        ->button(10)->width(0.25)
        ->button(11)->width(0.25)
        ->button(12)->width(0.25)
        ->button(13)->width(0.25)->toArray();

    expect($keyboard)->toBe([
        [['text' => '1'], ['text' => '2'], ['text' => '3'], ['text' => '4']],
        [['text' => '5']],
        [['text' => '6'], ['text' => '7'], ['text' => '8'], ['text' => '9']],
        [['text' => '10'], ['text' => '11'], ['text' => '12'], ['text' => '13']],
    ]);
});
