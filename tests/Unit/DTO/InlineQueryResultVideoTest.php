<?php

use DefStudio\Telegraph\DTO\InlineQueryResultVideo;

it('can export to array', function () {
    expect(
        InlineQueryResultVideo::make('a45', 'testVideoUrl', 'testMimeType', 'testThumbUrl', 'testTitle')
            ->caption('testCaption')
            ->description('testDescription')
            ->width(400)
            ->height(200)
            ->duration(10)
            ->toArray()
    )->toBe([
        'video_url' => 'testVideoUrl',
        'mime_type' => 'testMimeType',
        'thumb_url' => 'testThumbUrl',
        'title' => 'testTitle',
        'caption' => 'testCaption',
        'parse_mode' => 'html',
        'video_width' => 400,
        'video_height' => 200,
        'video_duration' => 10,
        'description' => 'testDescription',
        'id' => "a45",
        'type' => "video",
    ]);
});
