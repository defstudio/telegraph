<?php

use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;

test('Clear Telegraph Fake attached files for every new request', function () {
    TelegraphFacade::fake();
    $chat = make_chat();
    $chat->photo(Storage::path('photo.jpg'))->message('test');
    $telegraphB = $chat->message('123');
    $filesB = $telegraphB->toArray()['files'];

    expect($filesB)->toBeEmpty();
});
