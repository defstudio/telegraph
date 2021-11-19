<?php

use DefStudio\LaravelTelegraph\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/telegraph/{token}/webook', WebhookController::class)->name('telegraph.webhook');
