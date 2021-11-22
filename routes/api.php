<?php

use DefStudio\Telegraph\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/telegraph/{token}/webhook', [WebhookController::class, 'handle'])->name('telegraph.webhook');
