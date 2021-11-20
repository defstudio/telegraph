<?php

use Illuminate\Support\Facades\Http;
use function Pest\Laravel\artisan;

test('can retrieve telegram bot webhook info', function () {
    Http::fake([
        'https://api.telegram.org/bot123456AAABBB/setWebhook?url=http%3A%2F%2Flocalhost%2Ftelegraph%2F123456AAABBB%2Fwebhook' => Http::response([
            'ok' => true,
        ]),
    ]);

    /** @phpstan-ignore-next-line */
    artisan('telegraph:set-webhook')
        ->expectsOutput("Webhook updated")
        ->assertSuccessful();
});
