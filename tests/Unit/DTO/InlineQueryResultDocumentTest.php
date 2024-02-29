<?php

use DefStudio\Telegraph\DTO\InlineQueryResultDocument;

it('can export to array', function () {
    expect(
        InlineQueryResultDocument::make('a45', 'testTitle', 'testDocumentUrl', 'testMimeType')
            ->caption('testCaption')
            ->description('testDescription')
            ->thumbUrl('testThumbUrl')
            ->thumbWidth(400)
            ->thumbHeight(200)
            ->toArray()
    )->toBe([
        'title' => 'testTitle',
        'caption' => 'testCaption',
        'parse_mode' => 'html',
        'document_url' => 'testDocumentUrl',
        'mime_type' => 'testMimeType',
        'description' => 'testDescription',
        'thumb_url' => 'testThumbUrl',
        'thumb_width' => 400,
        'thumb_height' => 200,
        'id' => "a45",
        'type' => "document",
    ]);
});
