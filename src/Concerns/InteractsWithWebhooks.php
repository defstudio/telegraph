<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Exceptions\TelegramWebhookException;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait InteractsWithWebhooks
{
    private function getWebhookUrl(): string
    {
        /** @var string|null $customWebhookUrl */
        $customWebhookUrl = config('telegraph.custom_webhook_domain', config('telegraph.webhook.domain'));

        if ($customWebhookUrl === null) {
            $url = route('telegraph.webhook', $this->getBot());

            if (!str_starts_with($url, 'https://')) {
                throw TelegramWebhookException::invalidScheme();
            }

            return $url;
        }

        return $customWebhookUrl.route('telegraph.webhook', $this->getBot(), false);
    }

    /**
     * @param  bool|null  $dropPendingUpdates
     * @param  int|null  $maxConnections
     * @param  string|null  $secretToken
     * @param  string[]|null  $allowedUpdates
     *
     * @throws \DefStudio\Telegraph\Exceptions\TelegramWebhookException
     * @return \DefStudio\Telegraph\Telegraph
     */
    public function registerWebhook(?bool $dropPendingUpdates = null, ?int $maxConnections = null, ?string $secretToken = null, ?array $allowedUpdates = null): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SET_WEBHOOK;

        //@phpstan-ignore-next-line
        $telegraph->data = collect([
            'url' => $this->getWebhookUrl(),
            'drop_pending_updates' => $dropPendingUpdates,
            'max_connections' => $maxConnections ?? config('telegraph.webhook.max_connections'),
            'secret_token' => $secretToken ?? config('telegraph.webhook.secret_token'),
            'allowed_updates' => $allowedUpdates ?? config('telegraph.webhook.allowed_updates'),
        ])->filter()
            ->toArray();

        return $telegraph;
    }

    public function unregisterWebhook(bool $dropPendingUpdates = false): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_UNSET_WEBHOOK;
        $telegraph->data = [
            'drop_pending_updates' => $dropPendingUpdates,
        ];

        return $telegraph;
    }

    public function getWebhookDebugInfo(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_WEBHOOK_DEBUG_INFO;

        return $telegraph;
    }

    public function replyWebhook(int $callbackQueryId, string $message, bool $showAlert = false): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_ANSWER_WEBHOOK;
        $telegraph->data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $message,
            'show_alert' => $showAlert,
        ];

        return $telegraph;
    }

    public function answerPreCheckoutQuery(int $preCheckoutQueryId, bool $result, ?string $errorMessage = null): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_ANSWER_PRE_CHECKOUT_QUERY;
        $telegraph->data = [
            'pre_checkout_query_id' => $preCheckoutQueryId,
            'ok' => $result,
            'error_message' => $errorMessage,
        ];

        return $telegraph;
    }
}
