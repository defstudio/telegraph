<?php

use DefStudio\Telegraph\DTO\InlineQueryResult;
use DefStudio\Telegraph\Keyboard\Keyboard;

it('can export to array', function () {
    $fake = new class () extends InlineQueryResult {
        protected string $id = "99";
        protected string $type = 'fake';

        public function __construct()
        {
        }

        protected function data(): array
        {
            return [];
        }
    };

    expect($fake->keyboard(Keyboard::make()->button('foo')->url('https://bar.dev'))->toArray())->toBe([
        'id' => "99",
        'type' => "fake",
        'reply_markup' => [
            'inline_keyboard' => [
                [['text' => 'foo', 'url' => 'https://bar.dev']],
            ],
        ],
    ]);
});
