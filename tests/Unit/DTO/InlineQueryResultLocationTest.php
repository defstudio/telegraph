<?php

use DefStudio\Telegraph\DTO\InlineQueryResultLocation;

it('can export to array', function () {
    expect(
        InlineQueryResultLocation::make('a45', 'testTitle', 10.5, 10.5)
            ->thumbUrl('testThumbUrl')
            ->livePeriod(10)
            ->heading(10)
            ->proximityAlertRadius(5)
            ->thumbWidth(200)
            ->thumbHeight(100)
            ->horizontalAccuracy(10.5)
            ->toArray()
    )->
    toBe([
        'title' => 'testTitle',
        'latitude' => 10.5,
        'longitude' => 10.5,
        'thumb_url' => 'testThumbUrl',
        'live_period' => 10,
        'heading' => 10,
        'proximity_alert_radius' => 5,
        'thumb_width' => 200,
        'thumb_height' => 100,
        'horizontal_accuracy' => 10.5,
        'id' => "a45",
        'type' => "location",
    ]);
});
