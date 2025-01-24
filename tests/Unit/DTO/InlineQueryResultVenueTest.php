<?php

use DefStudio\Telegraph\DTO\InlineQueryResultVenue;

it('can export to array', function () {
    expect(
        InlineQueryResultVenue::make('a45', 'testTitle', 10.5, 10.5, 'testAddress')
            ->thumbUrl('testThumbUrl')
            ->thumbWidth(200)
            ->thumbHeight(100)
            ->foursquareId('testId')
            ->foursquareType('testType')
            ->googlePlaceId('testPlaceId')
            ->googlePlaceType('testPlaceType')
            ->toArray()
    )->
    toBe([
        'title' => 'testTitle',
        'latitude' => 10.5,
        'longitude' => 10.5,
        'address' => 'testAddress',
        'foursquare_id' => 'testId',
        'foursquare_type' => 'testType',
        'google_place_id' => 'testPlaceId',
        'google_place_type' => 'testPlaceType',
        'thumb_url' => 'testThumbUrl',
        'thumb_width' => 200,
        'thumb_height' => 100,
        'id' => "a45",
        'type' => "venue",
    ]);
});
