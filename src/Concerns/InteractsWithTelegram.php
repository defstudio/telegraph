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

    protected function sendRequestToTelegram(): Response
    {
        $asMultipart = $this->files->isNotEmpty();

        $request = $asMultipart
            ? Http::asMultipart()
            : Http::asJson();

        /** @var PendingRequest $request */
        $request = $this->files->reduce(
            /** @phpstan-ignore-next-line  */
            function ($request, Attachment $attachment, string $key) {
                return $request->attach($key, $attachment->contents(), $attachment->filename());
            },
            $request
        );

        return $request->post($this->getApiUrl(), $this->prepareData($asMultipart));
    }

    /**
     * @return array<string, mixed>
     */
    protected function prepareData(bool $asMultipart = false): array
    {
        $data = $this->data;

        if ($this->files->isNotEmpty() && !empty($data['text'])) {
            $data['caption'] = $data['text'];
            unset($data['text']);
        }

        if ($asMultipart) {
            $data = collect($data)
                ->mapWithKeys(function ($value, $key) {
                    if (!is_array($value)) {
                        return [$key => $value];
                    }

                    return [$key => json_encode($value)];
                })->toArray();
        }

        return $data;
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
