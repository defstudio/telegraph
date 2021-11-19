<?php

namespace DefStudio\LaravelTelegraph\Controllers;

use Illuminate\Http\Response;

class WebhookController
{
    public function handle(string $token): Response
    {
        abort_unless($token == config('telegraph.bot_token'), Response::HTTP_FORBIDDEN);

        $handler = config('telegraph.webhook_handler');
        app($handler)->handle(request());

        return \response()->noContent();
    }
}
