<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection LaravelFunctionsInspection */


use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Enums\ChatPermissions;
use DefStudio\Telegraph\Exceptions\ChatSettingsException;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Facades\Telegraph;

use function Spatie\PestPluginTestTime\testTime;
use function Spatie\Snapshots\assertMatchesSnapshot;

it('can customize the destination bot', function () {
    withfakeUrl();
    $bot = make_bot();

    $telegraph = Telegraph::bot($bot)
        ->registerWebhook();

    expect($telegraph->getApiUrl())->toStartWith("https://api.telegram.org/bot$bot->token/");
});

it('can customize the destination chat', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->html('foobar');
    })->toMatchTelegramSnapshot();
});

it('can retrieve bot info', function () {
    Telegraph::fake();
    $bot = make_bot();

    $response = Telegraph::bot($bot)->botInfo()->send();
    assertMatchesSnapshot($response->json('result'));
});

it('can register commands', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->bot(make_bot())->registerBotCommands([
            'foo' => 'first command',
            'bar' => 'second command',
        ]);
    })->toMatchTelegramSnapshot();
});

it('can unregister commands', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->bot(make_bot())->unregisterBotCommands();
    })->toMatchTelegramSnapshot();
});

it('can send a chat action', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->chatAction(ChatActions::TYPING);
    })->toMatchTelegramSnapshot();
});

it('can change chat title', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->setTitle('foo');
    })->toMatchTelegramSnapshot();
});

test('chat title cannot be empty', function () {
    Telegraph::chat(make_chat())->setTitle("");
})->throws(ChatSettingsException::class, 'Telegram Chat title cannot be empty');

test('chat title cannot overflow 255 chars', function () {
    Telegraph::chat(make_chat())->setTitle(str_repeat('a', 256));
})->throws(ChatSettingsException::class, "Telegram Chat title max length (255) exceeded");

it('can change chat description', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->setDescription('bar');
    })->toMatchTelegramSnapshot();
});

test('chat description cannot overflow 255 chars', function () {
    Telegraph::chat(make_chat())->setDescription(str_repeat('a', 256));
})->throws(ChatSettingsException::class, "Telegram Chat description max length (255) exceeded");

it('can change chat photo', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->setChatPhoto(Storage::path('photo.jpg'));
    })->toMatchTelegramSnapshot();
});

test('photo is validated', function (string $path, bool $valid, string $exceptionClass = null, string $exceptionMessage = null) {
    if ($valid) {
        expect(make_chat()->setChatPhoto(Storage::path($path)))
            ->toBeInstanceOf(\DefStudio\Telegraph\Telegraph::class);
    } else {
        expect(fn () => make_chat()->photo(Storage::path($path)))
            ->toThrow($exceptionClass, $exceptionMessage);
    }
})->with([
    'valid' => [
        'file' => 'photo.jpg',
        'valid' => true,
    ],
    'invalid weight' => [
        'file' => 'invalid_photo_size.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Photo size (10.340000 Mb) exceeds max allowed size of 10.000000 MB',
    ],
    'invalid ratio' => [
        'file' => 'invalid_photo_ratio_thin.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => "Ratio of height and width (22) exceeds max allowed height of 20",
    ],
    'invalid size' => [
        'file' => 'invalid_photo_ratio_huge.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Photo\'s sum of width and height (11000px) exceed allowed 10000px',
    ],
]);

it('can retrieve chat info', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->chatInfo();
    })->toMatchTelegramSnapshot();
});

it('can retrieve chat member count', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->chatMemberCount();
    })->toMatchTelegramSnapshot();
});

it('can generate a chat primary invite link', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->generateChatPrimaryInviteLink();
    })->toMatchTelegramSnapshot();
});

it('can create a chat invite link', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->createChatInviteLink();
    })->toMatchTelegramSnapshot();
});

it('can create a chat invite link with expiration', function () {
    testTime()->freeze('2021-01-02 12:34:56');

    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->createChatInviteLink()
            ->expire(today()->addDay());
    })->toMatchTelegramSnapshot();
});

it('can create a chat invite link with name', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->createChatInviteLink()
            ->name('foo');
    })->toMatchTelegramSnapshot();
});

it('can create a chat invite link with member limit', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->createChatInviteLink()
            ->memberLimit(42);
    })->toMatchTelegramSnapshot();
});

it('can create a chat invite link with a join request', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->createChatInviteLink()
            ->withJoinRequest();
    })->toMatchTelegramSnapshot();
});

it('can edit a chat invite link', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())->editChatInviteLink("https://t.me/123456");
    })->toMatchTelegramSnapshot();
});

it('can edit a chat invite link with expiration', function () {
    testTime()->freeze('2021-01-02 12:34:56');

    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->editChatInviteLink("https://t.me/123456")
            ->expire(today()->addDay());
    })->toMatchTelegramSnapshot();
});

it('can edit a chat invite link with name', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->editChatInviteLink("https://t.me/123456")
            ->name('foo');
    })->toMatchTelegramSnapshot();
});

it('can edit a chat invite link with member limit', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->editChatInviteLink("https://t.me/123456")
            ->memberLimit(42);
    })->toMatchTelegramSnapshot();
});

it('can edit a chat invite link with a join request', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->editChatInviteLink("https://t.me/123456")
            ->withJoinRequest();
    })->toMatchTelegramSnapshot();
});

it('can revoke a chat invite link', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->revokeChatInviteLink("https://t.me/123456");
    })->toMatchTelegramSnapshot();
});

it('can set chat permissions', function () {
    expect(function (\DefStudio\Telegraph\Telegraph $telegraph) {
        return $telegraph->chat(make_chat())
            ->setChatPermissions([
                ChatPermissions::CAN_INVITE_USERS,
                ChatPermissions::CAN_CHANGE_INFO,
                ChatPermissions::CAN_ADD_WEB_PAGE_PREVIEWS => true,
                ChatPermissions::CAN_SEND_MESSAGES => false,
            ]);
    })->toMatchTelegramSnapshot();
});
