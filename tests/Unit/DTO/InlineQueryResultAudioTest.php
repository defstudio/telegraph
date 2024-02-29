<?php

use DefStudio\Telegraph\DTO\InlineQueryResultAudio;

it('can export to array', function () {
    expect(
        InlineQueryResultAudio::make('a45', 'testAudioUrl', 'testTitle')
        ->caption('testCaption')
        ->performer('testPerformer')
        ->duration(10)
        ->toArray()
    )->toBe([
        'audio_url' => 'testAudioUrl',
        'title' => 'testTitle',
        'caption' => 'testCaption',
        'parse_mode' => 'html',
        'performer' => 'testPerformer',
        'audio_duration' => 10,
        'id' => "a45",
        'type' => "audio",
    ]);
});
