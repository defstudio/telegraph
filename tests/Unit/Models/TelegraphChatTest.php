<?php

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Storage;
use function Spatie\Snapshots\assertMatchesSnapshot;

it('can send a text message', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->message('foo')->send();

    Telegraph::assertSent('foo');
});

it('can send an html message', function () {
    $chat = make_chat();

    $telegraph = $chat->html('foo');

    assertMatchesSnapshot($telegraph->toArray());
});

it('can send a markdown message', function () {
    $chat = make_chat();

    $telegraph = $chat->markdown('foo');

    assertMatchesSnapshot($telegraph->toArray());
});

it('can replace a keyboard', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->replaceKeyboard(123456, Keyboard::make()->buttons([
        Button::make('test')->url('aaa'),
    ]))->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_REPLACE_KEYBOARD, [
        'reply_markup' => [
            'inline_keyboard' => [
                [
                    ['text' => 'test', 'url' => 'aaa'],
                ],
            ],
        ],
    ]);
});

it('can delete a keyboard', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->deleteKeyboard(123456)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_REPLACE_KEYBOARD, [
        'reply_markup' => '',
    ]);
});

it('can set a chat action', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->action(ChatActions::UPLOAD_DOCUMENT)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_CHAT_ACTION, [
        'chat_id' => $chat->chat_id,
        'action' => 'upload_document',
    ]);
});

it('can send a document', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->document(Storage::path('test.txt'))->markdown('test')->send();

    Telegraph::assertSentFiles(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_DOCUMENT, [
       'document' => new Attachment(Storage::path('test.txt'), 'test.txt'),
   ]);
});

it('can send a photo', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->photo(Storage::path('photo.jpg'))->markdown('test')->send();

    Telegraph::assertSentFiles(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_PHOTO, [
        'photo' => new Attachment(Storage::path('photo.jpg'), 'photo.jpg'),
    ]);
});
