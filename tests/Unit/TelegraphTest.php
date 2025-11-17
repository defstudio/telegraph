<?php

use DefStudio\Telegraph\Client\TelegraphResponse;
use DefStudio\Telegraph\Models\Concerns\HasCustomUrl;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Http;

test('custom Bots urls', function () {

    $bot = new class () extends TelegraphBot implements HasCustomUrl {
        public function getUrl(): string
        {
            return 'custom_url';
        }

        public function getFilesUrl(): string
        {
            return 'custom_files_url';
        }
    };

    $telegraph = app(Telegraph::class)->withEndpoint('endpoint')->bot($bot);

    expect($telegraph->getUrl())->toBe('custom_url/endpoint')
        ->and($telegraph->getFilesUrl())->toBe('custom_files_url');
});

test('sync sending returns a Telegraph Response', function () {
    Http::fake();

    $response = app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->send();

    expect($response)->toBeInstanceOf(TelegraphResponse::class);
});

test('async sending returns a Pending Dispatch', function () {
    Http::fake();

    $response = app(Telegraph::class)
        ->chat(make_chat())
        ->html('foobar')
        ->dispatch();

    expect($response)->toBeInstanceOf(PendingDispatch::class);
});

it('can handle conditional closures', function () {
    $count = 0;

    $telegraph = app(Telegraph::class)
        ->when(true, function (Telegraph $telegraph) use (&$count) {
            $count++;

            return $telegraph;
        })->when(false, function (Telegraph $telegraph) use (&$count) {
            $count += 10;

            return $telegraph;
        });

    expect($telegraph)->toBeInstanceOf(Telegraph::class)
        ->and($count)->toBe(1);
});
