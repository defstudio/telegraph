<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait InteractsWithWebhooks
{
    public function registerWebhook(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SET_WEBHOOK;
        $telegraph->data = [
            'url' => route('telegraph.webhook', $telegraph->getBot()),
        ];

        return $telegraph;
    }

    public function getWebhookDebugInfo(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_WEBHOOK_DEBUG_INFO;

        return $telegraph;
    }

    public function replyWebhook(int $callbackQueryId, string $message): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_ANSWER_WEBHOOK;
        $telegraph->data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $message,
        ];

        return $telegraph;
    }
}
