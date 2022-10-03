<?php

use DefStudio\Telegraph\DTO\InlineQueryResultContact;

it('can export to array', function () {
    expect(
        InlineQueryResultContact::make('a45', '3999999999', 'testFirstName')
        ->thumbWidth(400)
        ->thumbHeight(200)
        ->lastName('testLastName')
        ->vcard('testVcard')
        ->thumbUrl('testThumbUrl')
        ->toArray()
    )->toBe([
        'phone_number' => '3999999999',
        'first_name' => 'testFirstName',
        'last_name' => 'testLastName',
        'vcard' => 'testVcard',
        'thumb_url' => 'testThumbUrl',
        'thumb_width' => 400,
        'thumb_height' => 200,
        'id' => "a45",
        'type' => "contact",
    ]);
});
