<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Enums\ChatPermissions;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

use function Spatie\Snapshots\assertMatchesSnapshot;

test('name is set to ID if missing', function () {
    $bot = TelegraphBot::create([
        'token' => Str::uuid(),
    ]);

    $chat = $bot->chats()->create(['chat_id' => Str::uuid()]);

    expect($chat->name)->toBe("Chat #$chat->id");
});

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

it('can send a document from remote url', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->document('https://test.dev/document.pdf')->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_DOCUMENT, [
        'document' => 'https://test.dev/document.pdf',
    ]);
});

it('can send a document from file_id', function () {
    Telegraph::fake();
    $chat = make_chat();

    $uuid = Str::uuid();

    $chat->document($uuid)->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_DOCUMENT, [
        'document' => $uuid,
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

it('can send a photo from remote url', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->photo('https://test.dev/photo.jpg')->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_PHOTO, [
        'photo' => 'https://test.dev/photo.jpg',
    ]);
});

it('can send a photo from file_id', function () {
    Telegraph::fake();
    $chat = make_chat();

    $uuid = Str::uuid();

    $chat->photo($uuid)->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_PHOTO, [
        'photo' => $uuid,
    ]);
});

it('can send a voice', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->voice(Storage::path('voice.ogg'), 'test')->markdown('test')->send();

    Telegraph::assertSentFiles(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_VOICE, [
        'voice' => new Attachment(Storage::path('voice.ogg'), 'test'),
    ]);
});

it('can send a voice from remote url', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->voice('https://test.dev/voice.ogg')->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_VOICE, [
        'voice' => 'https://test.dev/voice.ogg',
    ]);
});

it('can send a voice from file_id', function () {
    Telegraph::fake();
    $chat = make_chat();

    $uuid = Str::uuid();

    $chat->voice($uuid)->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_VOICE, [
        'voice' => $uuid,
    ]);
});

it('can edit a message caption', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->editCaption(42)->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_EDIT_CAPTION, [
        'message_id' => 42,
        'caption' => 'test',
    ], false);
});

it('can delete a message', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->deleteMessage(42)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_DELETE_MESSAGE, [
        'message_id' => 42,
    ], false);
});

it('can pin a message', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->pinMessage(42)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_PIN_MESSAGE, [
        'message_id' => 42,
    ], false);
});

it('can unpin a message', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->unpinMessage(42)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_UNPIN_MESSAGE, [
        'message_id' => 42,
    ], false);
});

it('can unpin all messages', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->unpinAllMessages()->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_UNPIN_ALL_MESSAGES);
});

it('can set chat title', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->setTitle('foo')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SET_CHAT_TITLE, [
        'title' => 'foo',
    ], false);
});

it('can set chat description', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->setDescription('bar')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SET_CHAT_DESCRIPTION, [
        'description' => 'bar',
    ], false);
});

it('can retrieve its telegram info', function () {
    Telegraph::fake();
    $chat = make_chat();

    assertMatchesSnapshot($chat->info());
});

it('can retrieve its member count', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->memberCount();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_GET_CHAT_MEMBER_COUNT);
});

it('can generate a primary invite link', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->generatePrimaryInviteLink()->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_EXPORT_CHAT_INVITE_LINK);
});

it('can create an invite link', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->createInviteLink()->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_CREATE_CHAT_INVITE_LINK);
});

it('can edit an invite link', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->editInviteLink('https://t.me/123456')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_EDIT_CHAT_INVITE_LINK, [
        'invite_link' => 'https://t.me/123456',
    ], false);
});

it('can revoke an invite link', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->revokeInviteLink('https://t.me/123456')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_REVOKE_CHAT_INVITE_LINK, [
        'invite_link' => 'https://t.me/123456',
    ], false);
});

it('can set permissions', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->setPermissions([
        ChatPermissions::CAN_INVITE_USERS,
        ChatPermissions::CAN_CHANGE_INFO,
        ChatPermissions::CAN_ADD_WEB_PAGE_PREVIEWS => true,
        ChatPermissions::CAN_SEND_MESSAGES => false,
    ])->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SET_CHAT_PERMISSIONS, [
        'permissions' => [
            'can_invite_users' => true,
            'can_change_info' => true,
            'can_add_web_page_previews' => true,
            'can_send_messages' => false,
        ],
    ], false);
});
