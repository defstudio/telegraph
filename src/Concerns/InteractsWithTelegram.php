<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\Jobs\SendRequestToTelegramJob;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\Client\PendingRequest;
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

    protected string|null $baseUrl = null;

    protected string|null $httpProxy = null;

    protected function sendRequestToTelegram(): Response
    {
        $asMultipart = $this->files->isNotEmpty();

        $request = $asMultipart
            ? Http::asMultipart()
            : Http::asJson();

        /** @var PendingRequest $request */
        $request = $this->files->reduce(
            /** @phpstan-ignore-next-line */
            fn ($request, Attachment $attachment, string $key) => $request->attach(
                $key,
                $attachment->contents(),
                $attachment->filename()
            ),
            $request
        );

        // Apply proxy configuration if set
        if ($proxy = $this->getHttpProxy()) {
            $request->withOptions(['proxy' => $proxy]);
        }

        return $request->timeout(config('telegraph.http_timeout', 30)) //@phpstan-ignore-line
            ->connectTimeout(config('telegraph.http_connection_timeout', 10)) //@phpstan-ignore-line
            ->post($this->getApiUrl(), $this->prepareData());
    }

    /**
     * @return array<string, mixed>
     */
    protected function prepareData(): array
    {
        $asMultipart = $this->files->isNotEmpty();

        $data = $this->data;

        $data = $this->pipeTraits('preprocessData', $data);

        if ($asMultipart) {
            $data = collect($data)
                ->mapWithKeys(function ($value, $key) {
                    if (!is_array($value)) {
                        return [$key => $value];
                    }

                    return [$key => json_encode($value)];
                })
                ->toArray();
        }

        //@phpstan-ignore-next-line
        return $data;
    }

    protected function dispatchRequestToTelegram(?string $queue = null): PendingDispatch
    {
        return SendRequestToTelegramJob::dispatch($this->getApiUrl(), $this->prepareData(), $this->files, $this->getHttpProxy())->onQueue($queue);
    }

    public function setBaseUrl(string|null $url): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->baseUrl = $url;

        return $telegraph;
    }

    protected function getBaseUrl(): string
    {
        /* @phpstan-ignore-next-line */
        return Str::of($this->baseUrl ?? config('telegraph.telegram_api_url', 'https://api.telegram.org/'))
            ->rtrim('/')
            ->append('/', config('telegraph.token_prefix', 'bot')); //@phpstan-ignore-line
    }

    public function setHttpProxy(string|null $proxy): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->httpProxy = $proxy;

        return $telegraph;
    }

    protected function getHttpProxy(): string|null
    {
        /* @phpstan-ignore-next-line */
        return $this->httpProxy ?? config('telegraph.http_proxy');
    }

    protected function getFilesBaseUrl(): string
    {
        /* @phpstan-ignore-next-line */
        return Str::of($this->baseUrl ?? config('telegraph.telegram_api_url', 'https://api.telegram.org/'))
            ->rtrim('/')
            ->append('/file/', config('telegraph.token_prefix', 'bot')); //@phpstan-ignore-line
    }

    public function getUrl(): string
    {
        /** @phpstan-ignore-next-line */
        return (string) Str::of($this->getBaseUrl())
            ->append($this->getBotToken())
            ->append('/', $this->endpoint)
            ->when(!empty($this->data), fn (Stringable $str) => $str->append('?', http_build_query($this->data)));
    }

    public function getApiUrl(): string
    {
        /** @phpstan-ignore-next-line */
        return (string) Str::of($this->getBaseUrl())
            ->append($this->getBotToken())
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
