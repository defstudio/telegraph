<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Jobs\SendRequestToTelegramJob;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * @mixin Telegraph
 */
trait InteractsWithTelegram
{
    protected string $endpoint;

    /**
     * @throws TelegraphException
     */
    protected function sendRequestToTelegram(): Response
    {
        return Http::get($this->getUrl());
    }

    protected function dispatchRequestToTelegram(string $queue = null): PendingDispatch
    {
        return SendRequestToTelegramJob::dispatch($this->getUrl())->onQueue($queue);
    }

    protected function buildChatMessage(): void
    {
        $this->endpoint = self::ENDPOINT_MESSAGE;
        $this->data = [
            'text' => $this->message,
            'chat_id' => $this->getChat()->chat_id,
            'parse_mode' => $this->parseMode,
        ];

        if (!empty($this->keyboard)) {
            $this->data['reply_markup'] = json_encode([
                'inline_keyboard' => $this->keyboard,
            ]);
        }
    }

    public function getUrl(): string
    {
        if (empty($this->endpoint)) {
            $this->buildChatMessage();
        }

        /** @phpstan-ignore-next-line */
        return (string) Str::of(Telegraph::TELEGRAM_API_BASE_URL)
            ->append($this->getBot()->token)
            ->append('/', $this->endpoint)
            ->when(!empty($this->data), fn (Stringable $str) => $str->append('?', http_build_query($this->data)));
    }
}
