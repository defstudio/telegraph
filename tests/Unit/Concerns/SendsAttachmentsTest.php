<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Storage;

it('can send a document', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->document(Storage::path('test.txt'));
    })->toMatchTelegramSnapshot();
});

it('requires a chat to send a document', function () {
    TelegraphFacade::document(Storage::path('test.txt'));
})->throws(TelegraphException::class, 'No TelegraphChat defined for this request');

it('can attach a document while writing a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->markdown('look at **this** file!')
            ->document(Storage::path('test.txt'));
    })->toMatchTelegramSnapshot();
});

it('can attach a document with markdown caption', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->document(Storage::path('test.txt'))
            ->markdown('look at **this** file!');
    })->toMatchTelegramSnapshot();
});

it('can attach a document with html caption', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->document(Storage::path('test.txt'))
            ->markdown('look at <b>this</b> file!');
    })->toMatchTelegramSnapshot();
});

it('can disable content type detection', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph
            ->document(Storage::path('test.txt'))
            ->withoutContentTypeDetection();
    })->toMatchTelegramSnapshot();
});

it('can disable notification', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->document(Storage::path('test.txt'))
            ->silent();
    })->toMatchTelegramSnapshot();
});

it('can protect content from sharing', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->document(Storage::path('test.txt'))
            ->protected();
    })->toMatchTelegramSnapshot();
});

it('can send a document replying to a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->document(Storage::path('test.txt'))
            ->reply(1234);
    })->toMatchTelegramSnapshot();
});

it('can attach a keyboard to a document', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->document(Storage::path('test.txt'))
            ->keyboard(fn (Keyboard $keyboard) => $keyboard->button('def:studio')->url('https://defstudio.it'));
    })->toMatchTelegramSnapshot();
});

test('documents are validated', function (string $path, bool $valid, string $exceptionClass = null, string $exceptionMessage = null) {
    if ($valid) {
        expect(chat()->document(Storage::path($path)))
            ->toBeInstanceOf(Telegraph::class);
    } else {
        expect(fn () => chat()->document(Storage::path($path)))
            ->toThrow($exceptionClass, $exceptionMessage);
    }
})->with([
    'valid' => [
        'file' => 'valid_document.txt',
        'valid' => true,
    ],
    'not found' => [
        'file' => 'fake.txt',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'not found',
    ],
    'invalid size' => [
        'file' => 'invalid_document_size.txt',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Document size (50.010000 Mb) exceeds max allowed size of 50.000000 MB',
    ],
]);

it('can attach a thumbnail', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph
            ->document(Storage::path('test.txt'))
            ->thumbnail(Storage::path('thumbnail.jpg'));
    })->toMatchTelegramSnapshot();
});

test('thumbnails are validated', function (string $thumbnailPath, bool $valid, string $exceptionClass = null, string $exceptionMessage = null) {
    if ($valid) {
        expect(chat()->document(Storage::path('test.txt'))->thumbnail(Storage::path($thumbnailPath)))
            ->toBeInstanceOf(Telegraph::class);
    } else {
        expect(fn () => chat()->document(Storage::path('test.txt'))->thumbnail(Storage::path($thumbnailPath)))
            ->toThrow($exceptionClass, $exceptionMessage);
    }
})->with([
    'valid' => [
        'file' => 'thumbnail.jpg',
        'valid' => true,
    ],
    'not found' => [
        'file' => 'fake.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'not found',
    ],
    'invalid size' => [
        'file' => 'invalid_thumbnail_size.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail size (201.000000 Kb) exceeds max allowed size of 200.000000 Kb',
    ],
    'invalid height' => [
        'file' => 'invalid_thumbnail_height.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail height (321px) exceeds max allowed height of 320px',
    ],
    'invalid width' => [
        'file' => 'invalid_thumbnail_width.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Thumbnail width (321px) exceeds max allowed width of 320px',
    ],
    'invalid ext' => [
        'file' => 'invalid_thumbnail_ext.png',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'Invalid thumbnail extension (png). Only jpg are allowed',
    ],
]);

it('can send a location message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->location(12.345, -54.321);
    })->toMatchTelegramSnapshot();
});

it('can send a photo', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->photo(Storage::path('photo.jpg'));
    })->toMatchTelegramSnapshot();
});

it('requires a chat to send a photo', function () {
    TelegraphFacade::photo(Storage::path('photo.jpg'));
})->throws(TelegraphException::class, 'No TelegraphChat defined for this request');

it('can attach a photo while writing a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->markdown('look at **this** file!')
            ->photo(Storage::path('photo.jpg'));
    })->toMatchTelegramSnapshot();
});

it('can attach a photo with markdown caption', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->photo(Storage::path('photo.jpg'))
            ->markdown('look at **this** photo!');
    })->toMatchTelegramSnapshot();
});


it('can attach a photo with html caption', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->photo(Storage::path('photo.jpg'))
            ->markdown('look at <b>this</b> photo!');
    })->toMatchTelegramSnapshot();
});

it('can send a photo without notification', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->photo(Storage::path('photo.jpg'))
            ->silent();
    })->toMatchTelegramSnapshot();
});

it('can send a photo protectint it from sharing', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->photo(Storage::path('photo.jpg'))
            ->protected();
    })->toMatchTelegramSnapshot();
});

it('can send a photo replying to a message', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->photo(Storage::path('photo.jpg'))
            ->reply(1234);
    })->toMatchTelegramSnapshot();
});

it('can attach a keyboard to a photo', function () {
    expect(function (Telegraph $telegraph) {
        return $telegraph->photo(Storage::path('photo.jpg'))
            ->keyboard(fn (Keyboard $keyboard) => $keyboard->button('def:studio')->url('https://defstudio.it'));
    })->toMatchTelegramSnapshot();
});

test('photos are validated', function (string $path, bool $valid, string $exceptionClass = null, string $exceptionMessage = null) {
    if ($valid) {
        expect(chat()->photo(Storage::path($path)))
            ->toBeInstanceOf(Telegraph::class);
    } else {
        expect(fn () => chat()->photo(Storage::path($path)))
            ->toThrow($exceptionClass, $exceptionMessage);
    }
})->with([
    'valid' => [
        'file' => 'photo.jpg',
        'valid' => true,
    ],
    'not found' => [
        'file' => 'fake.jpg',
        'valid' => false,
        'exception' => FileException::class,
        'message' => 'not found',
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
