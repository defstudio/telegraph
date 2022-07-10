<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Controllers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController
{
    public function handle(Request $request, string $token): Response
    {
        $botModel = config('telegraph.models.bot');

        /** @var TelegraphBot|null $bot */
        $bot = $botModel::fromToken($token);

        /** @var class-string $handler */
        $handler = config('telegraph.webhook_handler');

        /** @var WebhookHandler $handler */
        $handler = app($handler);

        $handler->handle($request, $bot);

        return \response()->noContent();
    }
}
