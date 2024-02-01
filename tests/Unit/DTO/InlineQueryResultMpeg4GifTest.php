<?php

use DefStudio\Telegraph\DTO\InlineQueryResultMpeg4Gif;

it('can export to array', function () {
    expect(
        InlineQueryResultMpeg4Gif::make('a45', 'testMpeg4Url', 'testThumbUrl')
            ->mpeg4Width(400)
            ->mpeg4Height(200)
            ->mpeg4Duration(10)
            ->thumbMimeType('testThumbMimeType')
            ->title('testTitle')
            ->caption('testCaption')
            ->toArray()
    )->toBe([
        'mpeg4_url' => 'testMpeg4Url',
        'mpeg4_width' => 400,
        'mpeg4_height' => 200,
        'mpeg4_duration' => 10,
        'thumb_url' => 'testThumbUrl',
        'thumb_mime_type' => 'testThumbMimeType',
        'title' => 'testTitle',
        'caption' => 'testCaption',
        'parse_mode' => 'html',
        'id' => "a45",
        'type' => "mpeg4_gif",
    ]);
});
