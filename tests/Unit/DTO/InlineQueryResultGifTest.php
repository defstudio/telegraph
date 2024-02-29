<?php

use DefStudio\Telegraph\DTO\InlineQueryResultGif;

it('can export to array', function () {
    expect(
        InlineQueryResultGif::make('a45', 'https://gif.dev', 'https://thumb.gif.dev')
        ->width(200)
        ->height(400)
        ->duration(300)
        ->title('foo')
        ->caption('bar')
        ->toArray()
    )->toBe([
        'gif_url' => 'https://gif.dev',
        'thumb_url' => 'https://thumb.gif.dev',
        'gif_width' => 200,
        'gif_height' => 400,
        'gif_duration' => 300,
        'title' => 'foo',
        'caption' => 'bar',
        'parse_mode' => 'html',
        'id' => "a45",
        'type' => "gif",
    ]);
});
