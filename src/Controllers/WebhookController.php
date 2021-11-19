<?php

namespace DefStudio\LaravelTelegraph\Controllers;

use App\Actions\Telegram\HandleTelegramWebhook;
use Illuminate\Http\Response;

class WebhookController
{
    public function __invoke(string $token)
    {
        abort_unless($token == config('telegraph.bot_token'), Response::HTTP_FORBIDDEN);

        app(HandleTelegramWebhook::class)->handle(request());

        return \response()->noContent();
    }

}
