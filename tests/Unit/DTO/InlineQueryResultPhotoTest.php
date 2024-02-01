<?php

use DefStudio\Telegraph\DTO\InlineQueryResultPhoto;

it('can export to array', function () {
    expect(
        InlineQueryResultPhoto::make('a45', 'https://gif.dev', 'https://thumb.gif.dev')
        ->width(200)
        ->height(400)
        ->description('baz')
        ->title('foo')
        ->caption('bar')
        ->toArray()
    )->toBe([
        'photo_url' => 'https://gif.dev',
        'thumb_url' => 'https://thumb.gif.dev',
        'photo_width' => 200,
        'photo_height' => 400,
        'title' => 'foo',
        'caption' => 'bar',
        'parse_mode' => 'html',
        'description' => 'baz',
        'id' => "a45",
        'type' => "photo",
    ]);
});
