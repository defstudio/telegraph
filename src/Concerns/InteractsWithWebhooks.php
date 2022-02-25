<?php

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait InteractsWithWebhooks
{
    /**
     * @throws TelegraphException
     */
    public function registerWebhook(): Telegraph
    {
        if (empty($this->bot)) {
            throw TelegraphException::missingBot();
        }

        $this->endpoint = self::ENDPOINT_SET_WEBHOOK;
        $this->data = [
            'url' => route('telegraph.webhook', $this->bot),
        ];

        return $this;
    }

    public function getWebhookDebugInfo(): Telegraph
    {
        $this->endpoint = self::ENDPOINT_GET_WEBHOOK_DEBUG_INFO;

        return $this;
    }

    public function replyWebhook(string $callbackQueryId, string $message): Telegraph
    {
        $this->endpoint = self::ENDPOINT_ANSWER_WEBHOOK;
        $this->data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $message,
        ];

        return $this;
    }
}
