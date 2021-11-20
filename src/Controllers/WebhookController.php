<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\LaravelTelegraph\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymphonyResponse;

class WebhookController
{
    public function handle(Request $request, string $token): Response
    {
        abort_unless($token == config('telegraph.bot_token'), SymphonyResponse::HTTP_FORBIDDEN);

        /** @var class-string $handler */
        $handler = config('telegraph.webhook_handler');

        app($handler)->handle($request);

        return \response()->noContent();
    }
}
