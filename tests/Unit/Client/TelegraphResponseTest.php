<?php


use DefStudio\Telegraph\Facades\Telegraph;

$fake_response_data = [
    "ok" => true,
    "result" => [
        "message_id" => 41302,
        "sender_chat" => [
            "id" => "-123456789",
            "title" => "Test Chat",
            "type" => "channel",
        ],
        "date" => 1645952240,
        "text" => "foo",
    ],
];

it('wraps original response', function () use ($fake_response_data) {
    Telegraph::fake([
        \DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE => $fake_response_data,
    ]);
    $bot = make_bot();

    $response = Telegraph::bot($bot)->message('foo')->send();

    expect($response->body())->toMatchSnapshot();

    expect($response->json('ok'))->toBeTrue();
});

it('returns telegram request success', function () {
    Telegraph::fake();
    $bot = make_bot();

    $response = Telegraph::bot($bot)->message('foo')->send();

    expect($response->telegraphOk())->toBeTrue();
    expect($response->telegraphError())->toBeFalse();
});

it('returns telegram request failure', function () {
    Telegraph::fake([
        \DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE => ['ok' => false],
    ]);
    $bot = make_bot();

    $response = Telegraph::bot($bot)->message('foo')->send();

    expect($response->telegraphOk())->toBeFalse();
    expect($response->telegraphError())->toBeTrue();
});

it('returns telegram posted message id', function () use ($fake_response_data) {
    Telegraph::fake([
        \DefStudio\Telegraph\Telegraph::ENDPOINT_MESSAGE => $fake_response_data,
    ]);
    $bot = make_bot();

    $response = Telegraph::bot($bot)->message('foo')->send();

    expect($response->telegraphMessageId())->toBe(41302);
});
