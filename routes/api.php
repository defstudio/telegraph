<?php

use Illuminate\Support\Facades\Route;

Route::post('/telegraph/{token}/webook', [TelegramController::class, 'handle_webook'])->name('telegraph.webhook');
