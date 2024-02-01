<?php

use DefStudio\Telegraph\DTO\InlineQueryResultVoice;

it('can export to array', function () {
    expect(
        InlineQueryResultVoice::make('a45', 'testVoiceUrl', 'testTitle')
        ->caption('testCaption')
        ->duration(10)
        ->toArray()
    )->toBe([
        'voice_url' => 'testVoiceUrl',
        'title' => 'testTitle',
        'caption' => 'testCaption',
        'parse_mode' => 'html',
        'voice_duration' => 10,
        'id' => "a45",
        'type' => "voice",
    ]);
});
