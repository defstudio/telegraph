<?php

use DefStudio\Telegraph\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

if ($webhookUrl = config('telegraph.webhook.url', config('telegraph.webhook_url', '/telegraph/{token}/webhook'))) {

    Route::post($webhookUrl, [WebhookController::class, 'handle'])
        ->middleware()
        ->name('telegraph.webhook');

}

