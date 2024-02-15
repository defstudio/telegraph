<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Enums\Emojis;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

it('can send a document', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->document(Storage::path('test.txt')))
        ->toMatchTelegramSnapshot();
});

it('can send a dice', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->dice())
        ->toMatchTelegramSnapshot();
});

it('can send a dice with different emojis', function (string $emoji) {
    expect(fn (Telegraph $telegraph) => $telegraph->dice($emoji))
        ->toMatchTelegramSnapshot();
})->with([
    'DICE' => Emojis::DICE,
    'ARROW' => Emojis::ARROW,
    'BASKETBALL' => Emojis::BASKETBALL,
    'FOOTBALL' => Emojis::FOOTBALL,
    'BOWLING' => Emojis::BOWLING,
    'SLOT_MACHINE' => Emojis::SLOT_MACHINE,
]);

it('requires a chat to send a document', function () {
    TelegraphFacade::document(Storage::path('test.txt'));
})->throws(TelegraphException::class, 'No TelegraphChat defined for this request');

it('can attach a document while writing a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdown('look at **this** file!')->document(Storage::path('test.txt')))
        ->toMatchTelegramSnapshot();
});

it('can attach a document with markdown caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->document(Storage::path('test.txt'))->markdown('look at **this** file!'))
        ->toMatchTelegramSnapshot();
});

it('can attach a document with markdownV2 caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->document(Storage::path('test.txt'))->markdownV2('look at **this** file!'))
        ->toMatchTelegramSnapshot();
});

it('can attach a document with html caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->document(Storage::path('test.txt'))->markdown('look at <b>this</b> file!'))
        ->toMatchTelegramSnapshot();
});

it('can disable content type detection', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->document(Storage::path('test.txt'))->withoutContentTypeDetection())
        ->toMatchTelegramSnapshot();
});

it('can disable notification', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->document(Storage::path('test.txt'))->silent())
        ->toMatchTelegramSnapshot();
});

it('can protect content from sharing', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->document(Storage::path('test.txt'))->protected())
        ->toMatchTelegramSnapshot();
});

it('can send a document replying to a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->document(Storage::path('test.txt'))->reply(1234))
        ->toMatchTelegramSnapshot();
});

it('can attach a keyboard to a document', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->document(Storage::path('test.txt'))
            ->keyboard(fn (Keyboard $keyboard) => $keyboard->button('def:studio')->url('https://defstudio.it'));
    })->toMatchTelegramSnapshot();
});

test('documents are validated', function (string $path, bool $valid, string $exceptionClass = null, string $exceptionMessage = null, array $customConfigs = []) {

    foreach ($customConfigs as $key => $value) {
        Config::set($key, $value);
    }

    if ($valid) {
        expect(make_chat()->document(Storage::path($path)))
            ->toBeInstanceOf(Telegraph::class);
    } else {
        expect(fn () => make_chat()->document(Storage::path($path)))
            ->toThrow($exceptionClass, $exceptionMessage);
    }
})->with([
    'valid' => [
        'file' => 'valid_document.txt',
        'valid' => true,
    ],
    'invalid size' => [
        'file' => 'invalid_document_size.txt',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Document size (50.010000 Mb) exceeds max allowed size of 50.000000 MB',
    ],
    'valid custom size' => [
        'file' => 'invalid_document_size.txt',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.document.max_size_mb' => 50.01,
        ],
    ],
    'integer custom size' => [
        'file' => 'invalid_document_size.txt',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.document.max_size_mb' => 51,
        ],
    ],
    'invalid custom size' => [
        'file' => 'valid_document.txt',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Document size (50.000000 Mb) exceeds max allowed size of 49.999990 MB',
        'custom_configs' => [
            'telegraph.attachments.document.max_size_mb' => 49.99999,
        ],
    ],
]);

it('can attach a thumbnail', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph
            ->document(Storage::path('test.txt'))
            ->thumbnail(Storage::path('thumbnail.jpg'));
    })->toMatchTelegramSnapshot();
});

test('thumbnails are validated', function (string $thumbnailPath, bool $valid, string $exceptionClass = null, string $exceptionMessage = null, array $customConfigs = []) {
    foreach ($customConfigs as $key => $value) {
        Config::set($key, $value);
    }

    if ($valid) {
        expect(make_chat()->document(Storage::path('test.txt'))->thumbnail(Storage::path($thumbnailPath)))
            ->toBeInstanceOf(Telegraph::class);
    } else {
        expect(fn () => make_chat()->document(Storage::path('test.txt'))->thumbnail(Storage::path($thumbnailPath)))
            ->toThrow($exceptionClass, $exceptionMessage);
    }
})->with([
    'valid' => [
        'file' => 'thumbnail.jpg',
        'valid' => true,
    ],
    'invalid size' => [
        'file' => 'invalid_thumbnail_size.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail size (201.000000 Kb) exceeds max allowed size of 200.000000 Kb',
    ],
    'invalid custom size' => [
        'file' => 'thumbnail.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail size (12.550000 Kb) exceeds max allowed size of 12.540000 Kb',
        'custom_configs' => [
            'telegraph.attachments.thumbnail.max_size_kb' => 12.54,
        ],
    ],
    'valid custom size' => [
        'file' => 'invalid_thumbnail_size.jpg',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.thumbnail.max_size_kb' => 201,
        ],
    ],
    'invalid height' => [
        'file' => 'invalid_thumbnail_height.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail height (321px) exceeds max allowed height of 320px',
    ],
    'invalid custom height' => [
        'file' => 'thumbnail.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail height (320px) exceeds max allowed height of 319px',
        'custom_configs' => [
            'telegraph.attachments.thumbnail.max_height_px' => 319,
        ],
    ],
    'valid custom height' => [
        'file' => 'invalid_thumbnail_height.jpg',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.thumbnail.max_height_px' => 321,
        ],
    ],
    'invalid width' => [
        'file' => 'invalid_thumbnail_width.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail width (321px) exceeds max allowed width of 320px',
    ],
    'invalid custom width' => [
        'file' => 'thumbnail.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail width (320px) exceeds max allowed width of 319px',
        'custom_configs' => [
            'telegraph.attachments.thumbnail.max_width_px' => 319,
        ],
    ],
    'valid custom width' => [
        'file' => 'invalid_thumbnail_width.jpg',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.thumbnail.max_width_px' => 321,
        ],
    ],
    'invalid ext' => [
        'file' => 'invalid_thumbnail_ext.png',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Invalid thumbnail extension (png). Only jpg are allowed',
    ],
    'valid custom ext' => [
        'file' => 'invalid_thumbnail_ext.png',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.thumbnail.allowed_ext' => ['png'],
        ],
    ],
    'invalid custom ext' => [
        'file' => 'thumbnail.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Invalid thumbnail extension (jpg). Only png are allowed',
        'custom_configs' => [
            'telegraph.attachments.thumbnail.allowed_ext' => ['png'],
        ],
    ],
]);

it('can send a location message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->location(12.345, -54.321))
        ->toMatchTelegramSnapshot();
});

it('can send a contact', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->contact('3331122333', 'testFirstName'))
        ->toMatchTelegramSnapshot();
});

it('can send a photo', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->photo(Storage::path('photo.jpg')))
        ->toMatchTelegramSnapshot();
});

it('can send an animation', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->animation(Storage::path('gif.gif')))
        ->toMatchTelegramSnapshot();
});

it('can send a video', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->video(Storage::path('video.mp4')))
        ->toMatchTelegramSnapshot();
});

it('can send an audio', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->audio(Storage::path('audio.mp3')))
        ->toMatchTelegramSnapshot();
});

it('requires a chat to send a photo', function () {
    TelegraphFacade::photo(Storage::path('photo.jpg'));
})->throws(TelegraphException::class, 'No TelegraphChat defined for this request');

it('can attach a photo while writing a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdown('look at **this** file!')->photo(Storage::path('photo.jpg')))
        ->toMatchTelegramSnapshot();
});

it('can attach a photo with markdown caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->photo(Storage::path('photo.jpg'))->markdown('look at **this** photo!'))
        ->toMatchTelegramSnapshot();
});

it('can attach a photo with markdownV2 caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->photo(Storage::path('photo.jpg'))->markdownV2('look at **this** photo!'))
        ->toMatchTelegramSnapshot();
});

it('can attach a photo with html caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->photo(Storage::path('photo.jpg'))->html('look at <b>this</b> photo!'))
        ->toMatchTelegramSnapshot();
});

it('can send a photo without notification', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->photo(Storage::path('photo.jpg'))->silent())
        ->toMatchTelegramSnapshot();
});

it('can send a photo protecting it from sharing', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->photo(Storage::path('photo.jpg'))->protected())
        ->toMatchTelegramSnapshot();
});

it('can send a photo replying to a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->photo(Storage::path('photo.jpg'))->reply(1234))
        ->toMatchTelegramSnapshot();
});

it('can attach a keyboard to a photo', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph->photo(Storage::path('photo.jpg'))
            ->keyboard(fn (Keyboard $keyboard) => $keyboard->button('def:studio')->url('https://defstudio.it'))
    )->toMatchTelegramSnapshot();
});

test('photos are validated', function (string $path, bool $valid, string $exceptionClass = null, string $exceptionMessage = null, array $customConfigs = []) {

    foreach ($customConfigs as $key => $value) {
        Config::set($key, $value);
    }

    if ($valid) {
        expect(make_chat()->photo(Storage::path($path)))
            ->toBeInstanceOf(Telegraph::class);
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
    'valid custom weight' => [
        'file' => 'invalid_photo_size.jpg',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.photo.max_size_mb' => 10.34,
        ],
    ],
    'invalid custom weight' => [
        'file' => 'photo.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Photo size (0.030000 Mb) exceeds max allowed size of 0.010000 MB',
        'custom_configs' => [
            'telegraph.attachments.photo.max_size_mb' => 0.01,
        ],
    ],
    'invalid ratio' => [
        'file' => 'invalid_photo_ratio_thin.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => "Ratio of height and width (22.222222) exceeds max allowed ratio of 20.000000",
    ],
    'valid custom ratio' => [
        'file' => 'invalid_photo_ratio_thin.jpg',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.photo.max_ratio' => 23,
        ],
    ],
    'invalid custom ratio' => [
        'file' => 'photo.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => "Ratio of height and width (1.000000) exceeds max allowed ratio of 0.990000",
        'custom_configs' => [
            'telegraph.attachments.photo.max_ratio' => 0.99,
        ],
    ],
    'invalid size' => [
        'file' => 'invalid_photo_ratio_huge.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Photo\'s sum of width and height (11000px) exceed allowed 10000px',
    ],
    'valid custom size' => [
        'file' => 'invalid_photo_ratio_huge.jpg',
        'valid' => true,
        'exception' => null,
        'message' => null,
        'custom_configs' => [
            'telegraph.attachments.photo.height_width_sum_px' => 11000,
        ],
    ],
    'invalid custom size' => [
        'file' => 'photo.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Photo\'s sum of width and height (800px) exceed allowed 799px',
        'custom_configs' => [
            'telegraph.attachments.photo.height_width_sum_px' => 799,
        ],
    ],
]);

it('can send a voice', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->voice(Storage::path('voice.ogg')))
        ->toMatchTelegramSnapshot();
});

it('requires a chat to send a voice', function () {
    TelegraphFacade::voice(Storage::path('voice.ogg'));
})->throws(TelegraphException::class, 'No TelegraphChat defined for this request');

it('can attach a voice while writing a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->markdown('listen **this** one')->voice(Storage::path('voice.ogg')))
        ->toMatchTelegramSnapshot();
});

it('can attach a voice with markdown caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->voice(Storage::path('voice.ogg'))->markdown('listen **this** one'))
        ->toMatchTelegramSnapshot();
});

it('can attach a voice with markdownV2 caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->voice(Storage::path('voice.ogg'))->markdownV2('listen **this** one'))
        ->toMatchTelegramSnapshot();
});

it('can attach a voice with html caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->voice(Storage::path('voice.ogg'))->html('listen <b>this</b> one!'))
        ->toMatchTelegramSnapshot();
});

it('can send a voice without notification', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->voice(Storage::path('voice.ogg'))->silent())
        ->toMatchTelegramSnapshot();
});

it('can send a voice protecting it from sharing', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->voice(Storage::path('voice.ogg'))->protected())
        ->toMatchTelegramSnapshot();
});

it('can send a voice replying to a message', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->voice(Storage::path('voice.ogg'))->reply(1234))
        ->toMatchTelegramSnapshot();
});

it('can attach a keyboard to a voice', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph->voice(Storage::path('voice.ogg'))
            ->keyboard(fn (Keyboard $keyboard) => $keyboard->button('def:studio')->url('https://defstudio.it'))
    )->toMatchTelegramSnapshot();
});

test('voices are validated', function (string $path, bool $valid, string $exceptionClass = null, string $exceptionMessage = null) {
    if ($valid) {
        expect(make_chat()->voice(Storage::path($path)))
            ->toBeInstanceOf(Telegraph::class);
    } else {
        expect(fn () => make_chat()->photo(Storage::path($path)))
            ->toThrow($exceptionClass, $exceptionMessage);
    }
})->with([
    'valid' => [
        'file' => 'voice.ogg',
        'valid' => true,
    ],
]);

it('can edit a message caption', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->editCaption(42)->message('foo'))
        ->toMatchTelegramSnapshot();
});

it('can edit a media messages with a photo', function () {
    $photo_path = 'www.photoUrl.com';

    expect(fn (Telegraph $telegraph) => $telegraph->editMedia(42)->photo($photo_path))
        ->toMatchTelegramSnapshot();
});

it('can edit a media messages with a document', function () {
    $document_path = 'www.documentUrl.com';

    expect(fn (Telegraph $telegraph) => $telegraph->editMedia(42)->document($document_path))
        ->toMatchTelegramSnapshot();
});

it('can edit a media messages with an animation', function () {
    $animation_path = 'www.animationUrl.com';

    expect(fn (Telegraph $telegraph) => $telegraph->editMedia(42)->animation($animation_path))
        ->toMatchTelegramSnapshot();
});

it('can edit a media messages with a video', function () {
    $video_path = 'www.videoUrl.com';

    expect(fn (Telegraph $telegraph) => $telegraph->editMedia(42)->video($video_path))
        ->toMatchTelegramSnapshot();
});

it('can edit a media messages with an audio', function () {
    $video_path = 'www.audioUrl.com';

    expect(fn (Telegraph $telegraph) => $telegraph->editMedia(42)->audio($video_path))
        ->toMatchTelegramSnapshot();
});
