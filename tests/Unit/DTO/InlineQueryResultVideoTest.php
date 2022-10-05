<?php

use DefStudio\Telegraph\DTO\InlineQueryResultVideo;

it('can export to array', function () {
    expect(
        InlineQueryResultVideo::make('a45', 'testVideoUrl', 'testMimeType', 'testThumbUrl', 'testTitle')
            ->caption('testCaption')
            ->description('testDescription')
            ->videoWidth(400)
            ->videoHeight(200)
            ->videoDuration(10)
            ->toArray()
    )->toBe([
        'title' => 'testTitle',
        '$video_url' => 'testVideoUrl',
        '$mime_type' => 'testMimeType',
        '$thumb_url' => 'testThumbUrl',
        '$title' => 'testTitle',
        '$caption' => 'testCaption',
        '$description' => 'testDescription',
        '$video_width' => 400,
        '$video_height' => 200,
        '$video_duration' => 10,
        'id' => "a45",
        'type' => "video",
    ]);
});
