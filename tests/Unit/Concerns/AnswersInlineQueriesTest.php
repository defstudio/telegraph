<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\InlineQueryResultArticle;
use DefStudio\Telegraph\DTO\InlineQueryResultContact;
use DefStudio\Telegraph\DTO\InlineQueryResultGif;
use DefStudio\Telegraph\DTO\InlineQueryResultPhoto;
use DefStudio\Telegraph\Exceptions\InlineQueryException;
use DefStudio\Telegraph\Telegraph;

it('can answer an inline query', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->answerInlineQuery(99, [
        InlineQueryResultGif::make(42, 'https://gif.dev', 'https://gif-thumb.dev'),
        InlineQueryResultPhoto::make(42, 'https://photo.dev', 'https://photo-thumb.dev'),
        InlineQueryResultContact::make(42, '399999999', 'testFirstName'),
        InlineQueryResultArticle::make(42, 'testTitle', 'testMessage'),
    ]))->toMatchTelegramSnapshot();
});

it('can set cache duration', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph->answerInlineQuery(99, [
            InlineQueryResultGif::make(42, 'https://gif.dev', 'https://gif-thumb.dev'),
            InlineQueryResultPhoto::make(42, 'https://photo.dev', 'https://photo-thumb.dev'),
            InlineQueryResultContact::make(42, '399999999', 'testFirstName'),
            InlineQueryResultArticle::make(42, 'testTitle', 'testMessage'),
        ])->cache(600)
    )->toMatchTelegramSnapshot();
});

it('can set next offset', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph->answerInlineQuery(99, [
            InlineQueryResultGif::make(42, 'https://gif.dev', 'https://gif-thumb.dev'),
            InlineQueryResultPhoto::make(42, 'https://photo.dev', 'https://photo-thumb.dev'),
            InlineQueryResultContact::make(42, '399999999', 'testFirstName'),
            InlineQueryResultArticle::make(42, 'testTitle', 'testMessage'),
        ])->nextOffset('2')
    )->toMatchTelegramSnapshot();
});

it('can set results as personal', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph->answerInlineQuery(99, [
            InlineQueryResultGif::make(42, 'https://gif.dev', 'https://gif-thumb.dev'),
            InlineQueryResultPhoto::make(42, 'https://photo.dev', 'https://photo-thumb.dev'),
            InlineQueryResultContact::make(42, '399999999', 'testFirstName'),
            InlineQueryResultArticle::make(42, 'testTitle', 'testMessage'),
        ])->personal()
    )->toMatchTelegramSnapshot();
});

it('can offer to switch to private message', function () {
    expect(
        fn (Telegraph $telegraph) => $telegraph->answerInlineQuery(99, [
            InlineQueryResultGif::make(42, 'https://gif.dev', 'https://gif-thumb.dev'),
            InlineQueryResultPhoto::make(42, 'https://photo.dev', 'https://photo-thumb.dev'),
            InlineQueryResultContact::make(42, '399999999', 'testFirstName'),
            InlineQueryResultArticle::make(42, 'testTitle', 'testMessage'),
        ])->offertToSwitchToPrivateMessage('configure', '123456')
    )->toMatchTelegramSnapshot();
});

test('an exception is thrown if switch pm parameter is invalid', function () {
    bot()->answerInlineQuery(42, [])->offertToSwitchToPrivateMessage('test', 'invalid parameter');
})->throws(InlineQueryException::class, "Parameter [invalid parameter] for 'switch to private message' of InlineQueryAnswer is invalid. Only [A-Z, a-z, 0-9, _ and -] allowed");
