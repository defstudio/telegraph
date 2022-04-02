<?php /** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Storage;

it('can send a document', function () {
    expect(function (Telegraph $telegraph) {
        $telegraph->document(Storage::path('test.txt'))->toArray();
    })->toMatchTelegramSnapshot();
});

it('can attach a document while writing a message', function(){
    expect(function (Telegraph $telegraph) {
        $telegraph
            ->markdown('look at **this** file!')
            ->document(Storage::path('test.txt'))
            ->toArray();
    })->toMatchTelegramSnapshot();
});

it('can attach a document with a caption', function(){
    expect(function (Telegraph $telegraph) {
        $telegraph
            ->document(Storage::path('test.txt'))
            ->markdown('look at **this** file!')
            ->toArray();
    })->toMatchTelegramSnapshot();
});
