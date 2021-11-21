<?php

use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Telegraph;
use function Pest\Laravel\artisan;

test('can retrieve telegram bot webhook info', function () {
    Facade::fake([
        Telegraph::ENDPOINT_GET_WEBHOOK_DEBUG_INFO => [
            'ok' => true,
            'result' => [
                'url' => 'https://local.testing/telegraph/123456AAABBB/webhook',
                'has_custom_certificate' => false,
                'pending_update_count' => 0,
                'max_connections' => 40,
                'ip_address' => "1.234.567.890",
            ],
        ],
    ]);

    /** @phpstan-ignore-next-line */
    artisan('telegraph:debug-webhook')
        ->expectsOutput("url: https://local.testing/telegraph/123456AAABBB/webhook")
        ->expectsOutput("has_custom_certificate: no")
        ->expectsOutput("pending_update_count: 0")
        ->expectsOutput("max_connections: 40")
        ->expectsOutput("ip_address: 1.234.567.890")
        ->assertSuccessful();
});
