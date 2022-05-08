<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Photo;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

it('can get file info', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->getFileInfo('123456aaa'))
        ->toMatchTelegramSnapshot();
});

it('can store a file by id', function () {
    Storage::fake();

    Http::fake([
        "https://api.telegram.org/bot3f3814e1-5836-3d77-904e-60f64b15df36/getFile" => [
            'ok' => true,
            'result' => [
                'file_path' => 'photos/file_42.jpg',
            ],
        ],
        "https://api.telegram.org/file/bot3f3814e1-5836-3d77-904e-60f64b15df36/photos/file_42.jpg" => Http::response('test content'),
    ]);

    TelegraphFacade::bot(bot())->store('123456abc', Storage::path('images/bot'));

    expect(Storage::exists('images/bot/file_42.jpg'))->toBeTrue();
    expect(Storage::get('images/bot/file_42.jpg'))->toBe('test content');
});

it('can store a file from a downloadable', function () {
    Storage::fake();

    Http::fake([
        "https://api.telegram.org/bot3f3814e1-5836-3d77-904e-60f64b15df36/getFile" => [
            'ok' => true,
            'result' => [
                'file_path' => 'photos/file_99.jpg',
            ],
        ],
        "https://api.telegram.org/file/bot3f3814e1-5836-3d77-904e-60f64b15df36/photos/file_99.jpg" => Http::response('test content 2'),
    ]);

    $downloadable = Photo::fromArray([
        "file_id" => "123456abc",
        "file_size" => 1514,
        "width" => 90,
        "height" => 84,
    ]);

    TelegraphFacade::bot(bot())->store($downloadable, Storage::path('images/bot'));

    expect(Storage::exists('images/bot/file_99.jpg'))->toBeTrue();
    expect(Storage::get('images/bot/file_99.jpg'))->toBe('test content 2');
});

it('can store a file setting its name', function () {
    Storage::fake();


    Http::fake([
        "https://api.telegram.org/bot3f3814e1-5836-3d77-904e-60f64b15df36/getFile" => [
            'ok' => true,
            'result' => [
                'file_path' => 'photos/file_66.jpg',
            ],
        ],
        "https://api.telegram.org/file/bot3f3814e1-5836-3d77-904e-60f64b15df36/photos/file_66.jpg" => Http::response('test content 3'),
    ]);

    $downloadable = Photo::fromArray([
        "file_id" => "123456abc",
        "file_size" => 1514,
        "width" => 90,
        "height" => 84,
    ]);

    TelegraphFacade::bot(bot())->store($downloadable, Storage::path('images/bot'), 'My Photo.jpg');

    expect(Storage::exists('images/bot/file_66.jpg'))->toBeFalse();
    expect(Storage::exists('images/bot/My Photo.jpg'))->toBeTrue();
    expect(Storage::get('images/bot/My Photo.jpg'))->toBe('test content 3');
});

it('thows an exception if fail to retrieve file info', function () {
    Http::fake([
        "https://api.telegram.org/bot3f3814e1-5836-3d77-904e-60f64b15df36/getFile" => [
            'ok' => false,
            "error_code" => 400,
            "description" => "Bad Request: invalid file_id",
        ],
    ]);

    TelegraphFacade::bot(bot())->store('123456abc', Storage::path('images/bot'), 'My Photo.jpg');
})->throws(FileException::class, 'Failed to retreive info for file [123456abc]');

it('throws an error if fail to download the file', function () {
    Storage::fake();

    Http::fake([
        "https://api.telegram.org/bot3f3814e1-5836-3d77-904e-60f64b15df36/getFile" => [
            'ok' => true,
            'result' => [
                'file_path' => 'photos/file_42.jpg',
            ],
        ],
        "https://api.telegram.org/file/bot3f3814e1-5836-3d77-904e-60f64b15df36/photos/file_42.jpg" => Http::response('', 404),
    ]);

    TelegraphFacade::bot(bot())->store('123456abc', Storage::path('images/bot'));
})->throws(FileException::class, 'An error occourred while trying to download file [123456abc]');
