<?php

use DefStudio\Telegraph\DTO\InlineQueryResultArticle;

it('can export to array', function () {
    expect(
        InlineQueryResultArticle::make('a45', 'testTitle', 'testMessage')
            ->url('https://gif.dev')
            ->description('testDescription')
            ->thumbUrl('https://thumb.gif.dev')
            ->thumbWidth(400)
            ->thumbHeight(200)
            ->hideUrl(1)
            ->toArray()
    )->toBe([
        'title' => 'testTitle',
        'url' => 'https://gif.dev',
        'hide_url' => true,
        'description' => 'testDescription',
        'thumbnail_url' => 'https://thumb.gif.dev',
        'thumbnail_width' => 400,
        'thumbnail_height' => 200,
        'input_message_content' => [
            'message_text' => 'testMessage',
            'parse_mode' => 'html',
        ],
        'id' => "a45",
        'type' => "article",
    ]);
});
