<?php

use DefStudio\Telegraph\Facades\Telegraph;
use PHPUnit\Framework\ExpectationFailedException;

it('can return a custom response', function () {
    Telegraph::fake([
       \DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE => ['result' => 'oooook'],
   ]);

    $bot = make_bot();

    $response = Telegraph::bot($bot)->message('foo')->send();

    expect($response->json('result'))->toBe('oooook');
});

it('asserts a message is sent', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->message('foo')->send();

    Telegraph::assertSent('foo');
});

it('fails if the given message is not sent', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->message('foo')->send();

    Telegraph::assertSent('bar');
})->throws(ExpectationFailedException::class, 'Failed to assert that a request was sent to [sendMessage] endpoint with the given data (sent 1 requests so far)');;

it('asserts a partial message is sent', function () {
    $bot = make_bot();

    Telegraph::fake();

    Telegraph::bot($bot)->message('foo bar baz')->send();

    Telegraph::assertSent('baz', exact: false);
});

it('fails if exact message sent check is found partially', function () {
    Telegraph::fake();
    $bot = make_bot();


    Telegraph::bot($bot)->message('foo bar baz')->send();

    Telegraph::assertSent('baz');
})->throws(ExpectationFailedException::class, 'Failed to assert that a request was sent to [sendMessage] endpoint with the given data (sent 1 requests so far)');

it('asserts that no messages are sent', function () {
    Telegraph::fake();

    Telegraph::assertNothingSent();
});

it('fails if an unexpected message is sent', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->message('foo bar baz')->send();

    Telegraph::assertNothingSent();
})->throws(ExpectationFailedException::class, 'Failed to assert that no request were sent (sent 1 requests so far)');

it('asserts data was sent', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->message('foo bar baz')->send();
    Telegraph::bot($bot)->deleteKeyboard(42)->send();

    Telegraph::assertSentData(DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE, ['text' => 'foo bar baz']);
    Telegraph::assertSentData(DefStudio\Telegraph\Telegraph::ENDPOINT_REPLACE_KEYBOARD, [
        'chat_id' => $bot->chats->first()->chat_id,
        'message_id' => 42,
        'reply_markup' => null,
    ]);
});

it('fails if the expected data was not sent', function () {
    Telegraph::fake();

    Telegraph::assertSentData(DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE, ['text' => 'foo bar baz']);
})->throws(ExpectationFailedException::class, 'Failed to assert that a request was sent to [sendMessage] endpoint with the given data (sent 0 requests so far)');

it('fails if the expected data differs from the sent one', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->message('foo bar baz')->send();

    Telegraph::assertSentData(DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE, ['text' => 'foo bar']);
})->throws(ExpectationFailedException::class, 'Failed to assert that a request was sent to [sendMessage] endpoint with the given data (sent 1 requests so far)');

it('asserts a webhook has been registered', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->registerWebhook()->send();

    Telegraph::assertRegisteredWebhook();
});

it('fails if the expected webhook has not been registered', function () {
    Telegraph::fake();

    Telegraph::assertRegisteredWebhook();
})->throws(ExpectationFailedException::class, 'Failed to assert that a request was sent to [setWebhook] endpoint (sent 0 requests so far)');

it('asserts webhook debug info have been requested', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->getWebhookDebugInfo()->send();

    Telegraph::assertRequestedWebhookDebugInfo();
});

it('fails if the expected webhook debug info have not been requested', function () {
    Telegraph::fake();

    Telegraph::assertRequestedWebhookDebugInfo();
})->throws(ExpectationFailedException::class, 'Failed to assert that a request was sent to [getWebhookInfo] endpoint (sent 0 requests so far)');

it('asserts a webhook reply has been sent', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->replyWebhook(44, 'hello')->send();

    Telegraph::assertRepliedWebhook('hello');
});

it('fails if the wrong webhook reply has been sent', function () {
    Telegraph::fake();
    $bot = make_bot();

    Telegraph::bot($bot)->replyWebhook(44, 'hello')->send();

    Telegraph::assertRepliedWebhook('foo');
})->throws(ExpectationFailedException::class, 'Failed to assert that a request was sent to [answerCallbackQuery] endpoint with the given data (sent 1 requests so far)');

it('fails if the expected webhook reply has not been sent', function () {
    Telegraph::fake();

    Telegraph::assertRepliedWebhook('foo');
})->throws(ExpectationFailedException::class, 'Failed to assert that a request was sent to [answerCallbackQuery] endpoint with the given data (sent 0 requests so far)');
