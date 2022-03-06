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
        $this->endpoint = self::ENDPOINT_SET_WEBHOOK;
        $this->data = [
            'url' => route('telegraph.webhook', $this->getBot()),
        ];

        return $this;
    }

    public function getWebhookDebugInfo(): Telegraph
    {
        $this->endpoint = self::ENDPOINT_GET_WEBHOOK_DEBUG_INFO;

        return $this;
    }

    public function replyWebhook(int $callbackQueryId, string $message): Telegraph
    {
        $this->endpoint = self::ENDPOINT_ANSWER_WEBHOOK;
        $this->data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $message,
        ];

        return $this;
    }
}
