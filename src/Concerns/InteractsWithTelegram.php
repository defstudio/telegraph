<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\Attachment;
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

    protected function sendRequestToTelegram(): Response
    {
        $request = $this->files->isEmpty()
            ? Http::asJson()
            : Http::asMultipart();

        $request = $this->files->reduce(
            fn ($request, Attachment $attachment, string $key) => $request->attach($key, $attachment->contents(), $attachment->filename()),
            $request
        );

        return $request->post($this->getApiUrl(), $this->prepareData());
    }

    protected function prepareData(): array
    {
        if ($this->files->isNotEmpty() && !empty($this->data['text'])) {
            $this->data['caption'] = $this->data['text'];
            unset($this->data['text']);
        }

        return $this->data;
    }

    protected function dispatchRequestToTelegram(string $queue = null): PendingDispatch
    {
        return SendRequestToTelegramJob::dispatch($this->getApiUrl(), $this->data)->onQueue($queue);
    }

    public function getUrl(): string
    {
        /** @phpstan-ignore-next-line */
        return (string) Str::of(Telegraph::TELEGRAM_API_BASE_URL)
            ->append($this->getBot()->token)
            ->append('/', $this->endpoint)
            ->when(!empty($this->data), fn (Stringable $str) => $str->append('?', http_build_query($this->data)));
    }

    public function getApiUrl(): string
    {
        /** @phpstan-ignore-next-line */
        return (string) Str::of(Telegraph::TELEGRAM_API_BASE_URL)
            ->append($this->getBot()->token)
            ->append('/', $this->endpoint);
    }

    /**
     * @return array{url:string, payload:array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'url' => $this->getApiUrl(),
            'payload' => $this->prepareData(),
            'files' => $this->files->toArray(),
        ];
    }
}
