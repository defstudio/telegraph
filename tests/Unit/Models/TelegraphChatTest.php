<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Enums\ChatAdminPermissions;
use DefStudio\Telegraph\Enums\ChatPermissions;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Support\Testing\Fakes\TelegraphEditMediaFake;
use DefStudio\Telegraph\Support\Testing\Fakes\TelegraphPollFake;
use DefStudio\Telegraph\Support\Testing\Fakes\TelegraphQuizFake;
use DefStudio\Telegraph\Support\Testing\Fakes\TelegraphSetChatMenuButtonFake;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    expect($telegraph->toArray())->toMatchSnapshot();
});

it('can send a markdown message', function () {
    $chat = make_chat();

    $telegraph = $chat->markdown('foo');

    expect($telegraph->toArray())->toMatchSnapshot();
});

it('can send a markdownV2 message', function () {
    $chat = make_chat();

    $telegraph = $chat->markdownV2('foo');

    expect($telegraph->toArray())->toMatchSnapshot();
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

it('can send an animation', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->animation(Storage::path('gif.gif'))->markdown('test')->send();

    Telegraph::assertSentFiles(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_ANIMATION, [
        'animation' => new Attachment(Storage::path('gif.gif'), 'gif.gif'),
    ]);
});

it('can send a contact', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->contact('3331122333', 'testFirstName')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_CONTACT, [
        'phone_number' => '3331122333',
        'first_name' => 'testFirstName',
    ], false);
});

it('can send a animation from remote url', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->animation('https://test.dev/gif.gif')->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_ANIMATION, [
        'animation' => 'https://test.dev/gif.gif',
    ]);
});

it('can send a animation from file_id', function () {
    Telegraph::fake();
    $chat = make_chat();

    $uuid = Str::uuid();

    $chat->animation($uuid)->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_ANIMATION, [
        'animation' => $uuid,
    ]);
});

it('can send a video', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->video(Storage::path('video.mp4'))->markdown('test')->send();

    Telegraph::assertSentFiles(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_VIDEO, [
        'video' => new Attachment(Storage::path('video.mp4'), 'video.mp4'),
    ]);
});


it('can send a video from remote url', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->video('https://test.dev/video.mp4')->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_VIDEO, [
        'video' => 'https://test.dev/video.mp4',
    ]);
});

it('can send a video from file_id', function () {
    Telegraph::fake();
    $chat = make_chat();

    $uuid = Str::uuid();

    $chat->video($uuid)->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_VIDEO, [
        'video' => $uuid,
    ]);
});

it('can send an audio', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->audio(Storage::path('audio.mp3'))->markdown('test')->send();

    Telegraph::assertSentFiles(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_AUDIO, [
        'audio' => new Attachment(Storage::path('audio.mp3'), 'audio.mp3'),
    ]);
});

it('can send an audio from remote url', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->audio('https://test.dev/audio.mp3')->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_AUDIO, [
        'audio' => 'https://test.dev/audio.mp3',
    ]);
});

it('can send an audio from file_id', function () {
    Telegraph::fake();
    $chat = make_chat();

    $uuid = Str::uuid();

    $chat->audio($uuid)->markdown('test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_AUDIO, [
        'audio' => $uuid,
    ]);
});

it('can send a sticker', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->sticker(Storage::path('sticker.tgs'))->send();

    Telegraph::assertSentFiles(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_STICKER, [
        'sticker' => new Attachment(Storage::path('sticker.tgs'), 'sticker.tgs'),
    ]);
});

it('can send a sticker from remote url', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->sticker('https://test.dev/sticker.tgs')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_STICKER, [
        'sticker' => 'https://test.dev/sticker.tgs',
    ]);
});

it('can send a sticker from file_id', function () {
    Telegraph::fake();
    $chat = make_chat();

    $uuid = Str::uuid();

    $chat->sticker($uuid)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_STICKER, [
        'sticker' => $uuid,
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

it('can edit a media messages with a photo', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->editMedia(42)->photo('www.newMediaUrl.com')->send();

    TelegraphEditMediaFake::assertSentEditMedia('photo', 'www.newMediaUrl.com');
});

it('can edit a media messages with a document', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->editMedia(42)->document('www.newMediaUrl.com')->send();

    TelegraphEditMediaFake::assertSentEditMedia('document', 'www.newMediaUrl.com');
});

it('can edit a media messages with an animation', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->editMedia(42)->animation('www.newMediaUrl.com')->send();

    TelegraphEditMediaFake::assertSentEditMedia('animation', 'www.newMediaUrl.com');
});

it('can edit a media messages with a video', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->editMedia(42)->video('www.newMediaUrl.com')->send();

    TelegraphEditMediaFake::assertSentEditMedia('video', 'www.newMediaUrl.com');
});

it('can edit a media messages with an audio', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->editMedia(42)->audio('www.newMediaUrl.com')->send();

    TelegraphEditMediaFake::assertSentEditMedia('audio', 'www.newMediaUrl.com');
});

it('can delete a message', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->deleteMessage(42)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_DELETE_MESSAGE, [
        'message_id' => 42,
    ], false);
});

it('can delete messages', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->deleteMessages([23, 42])->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_DELETE_MESSAGES, [
        'message_ids' => [23, 42],
    ]);
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

it('can create a forum topic', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->createForumTopic('test name', 7322096, 'emoji_id')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_CREATE_FORUM_TOPIC);
});

it('can edit a forum topic', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->editForumTopic(123456, 'new test name', 'emoji_id')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_EDIT_FORUM_TOPIC);
});

it('can close a forum topic', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->closeForumTopic(123456)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_CLOSE_FORUM_TOPIC);
});

it('can reopen a forum topic', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->reopenForumTopic(7322096)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_REOPEN_FORUM_TOPIC);
});

it('can delete a forum topic', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->deleteForumTopic(7322096)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_DELETE_FORUM_TOPIC);
});

it('can delete a chat photo', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->deleteChatPhoto()->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_DELETE_CHAT_PHOTO);
});

it('can leave a chat', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->leave()->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_LEAVE_CHAT);
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

    expect($chat->info())->toMatchSnapshot();
});

it('can retrieve its member count', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->memberCount();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_GET_CHAT_MEMBER_COUNT);
});

it('can retrieve its member info', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->memberInfo(123456);

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_GET_CHAT_MEMBER);
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

it('can ban a chat member', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->banMember(123456)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_BAN_CHAT_MEMBER, [
        'user_id' => 123456,
    ], false);
});

it('can unban a chat member', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->unbanMember(123456)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_UNBAN_CHAT_MEMBER, [
        'user_id' => 123456,
    ], false);
});

it('can restrict a chat member', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->restrictMember(123456, [
        ChatPermissions::CAN_PIN_MESSAGES => false,
        ChatPermissions::CAN_INVITE_USERS => true,
        ChatPermissions::CAN_SEND_MESSAGES,
    ])->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_RESTRICT_CHAT_MEMBER, [
        'user_id' => 123456,
        'permissions' => [
            'can_pin_messages' => false,
            'can_invite_users' => true,
            'can_send_messages' => true,
        ],
    ], false);
});

it('can promote a chat member', function () {
    Telegraph::fake();
    $chat = make_chat();


    $chat->promoteMember(123456, [
        ChatAdminPermissions::CAN_PIN_MESSAGES => false,
        ChatAdminPermissions::CAN_INVITE_USERS => true,
        ChatAdminPermissions::CAN_CHANGE_INFO,
    ])->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_PROMOTE_CHAT_MEMBER, [
        'user_id' => 123456,
        'can_pin_messages' => false,
        'can_invite_users' => true,
        'can_change_info' => true,
    ], false);
});

it('can demote a chat member', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->demoteMember(123456)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_PROMOTE_CHAT_MEMBER, [
        'user_id' => 123456,
        'can_manage_chat' => false,
        'can_post_messages' => false,
        'can_delete_messages' => false,
        'can_manage_video_chats' => false,
        'can_restrict_members' => false,
        'can_promote_members' => false,
        'can_change_info' => false,
        'can_invite_users' => false,
        'can_pin_messages' => false,

    ], false);
});

it('can create a poll', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->poll('foo?')->option('bar!')->option('baz!')->send();

    TelegraphPollFake::assertSentPoll('foo?', ['bar!', 'baz!']);
});

it('can create a quiz', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->quiz('foo?')->option('bar!')->option('baz!', true)->send();

    TelegraphQuizFake::assertSentQuiz('foo?', ['bar!', 'baz!'], 1);
});

it('can forward a message', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->forwardMessage($chat, 123)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_FORWARD_MESSAGE, [
        'from_chat_id' => $chat->chat_id,
        'message_id' => 123,
    ]);
});
it('can copy a message', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->copyMessage($chat, 123)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_COPY_MESSAGE, [
        'from_chat_id' => $chat->chat_id,
        'message_id' => 123,
    ]);
});

it('can retrieve current chat menu button', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->menuButton()->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_GET_CHAT_MENU_BUTTON);
});

it('can restore default menu button', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->setMenuButton()->default()->send();

    TelegraphSetChatMenuButtonFake::assertChangedMenuButton('default');
});

it('can set commands menu button', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->setMenuButton()->commands()->send();

    TelegraphSetChatMenuButtonFake::assertChangedMenuButton('commands');
});

it('can set web app menu button', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->setMenuButton()->webApp("VISIT", "https://my-web.app")->send();

    TelegraphSetChatMenuButtonFake::assertChangedMenuButton('web_app', [
        'text' => "VISIT",
        'web_app' => [
            'url' => "https://my-web.app",
        ],
    ]);
});

it('can send request with custom endpoint and data', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->withEndpoint('custom-endpoint')->withData('sample', 'test')->send();

    Telegraph::assertSentData('custom-endpoint', [
        'sample' => 'test',
    ]);
});

it('can edit Telegraph data before sending a media ', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->withData('caption', 'test')->video('test.url')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_VIDEO, [
        'video' => 'test.url',
        'caption' => 'test',
    ]);
});

it('can edit Telegraph data after sending a media ', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->video('test.url')->withData('caption', 'test')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_VIDEO, [
        'video' => 'test.url',
        'caption' => 'test',
    ]);
});

it('can send a mediaGroup from remote url', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->mediaGroup([
        [
            'type' => 'photo',
            'media' => 'https://test.dev/photo.jpg',
        ],
        [
            'type' => 'photo',
            'media' => 'https://test.dev/photo.jpg',
        ],
    ])->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SEND_MEDIA_GROUP, [
        'media' => [
            [
                'type' => 'photo',
                'media' => 'https://test.dev/photo.jpg',
            ],
            [
                'type' => 'photo',
                'media' => 'https://test.dev/photo.jpg',
            ],
        ],
    ]);
});

it('can send a message to a specific Thread after', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->message('foo')->inThread(5)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE, [
        'text' => 'foo',
        'message_thread_id' => 5,
    ]);
});

it('can send a message to a specific Thread before', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->inThread(5)->message('foo')->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE, [
        'text' => 'foo',
        'message_thread_id' => 5,
    ]);
});

it('can accept chat join request', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->approveJoinRequest(123456)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_APPROVE_CHAT_JOIN_REQUEST, [
        'user_id' => 123456,
    ], false);
});

it('can decline chat join request', function () {
    Telegraph::fake();
    $chat = make_chat();

    $chat->declineJoinRequest(123456)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_DECLINE_CHAT_JOIN_REQUEST, [
        'user_id' => 123456,
    ], false);
});

it('can react on a message', function () {
    Telegraph::fake();
    $chat = make_chat();
    $reaction = ['type' => 'emoji', 'emoji' => '👍'];

    $chat->setMessageReaction(42, $reaction, false)->send();

    Telegraph::assertSentData(\DefStudio\Telegraph\Telegraph::ENDPOINT_SET_MESSAGE_REACTION, [
        'message_id' => 42,
        'reaction' => json_encode([$reaction]),
        'is_big' => false,
    ]);
});
