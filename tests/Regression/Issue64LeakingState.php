<?php

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Keyboard;

test("telegraph state doesn't leak between calls", function () {
    Telegraph::fake();

    $chat = make_chat();

    $chat->markdown('*action*')
        ->keyboard(
            Keyboard::make()
            ->button('do it')->action('do')
        )->send();

    $array = $chat->message('test')->toArray();

    expect($array)->toBe([
        'url' => 'https://api.telegram.org/bot3f3814e1-5836-3d77-904e-60f64b15df36/sendMessage',
        'payload' => [
            'text' => 'test',
            'chat_id' => '-123456789',
            'parse_mode' => 'html',
        ],
        'files' => [],
    ]);
});
