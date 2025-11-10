<?php

namespace DefStudio\Telegraph\Jobs;

use DefStudio\Telegraph\DTO\Attachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class SendRequestToTelegramJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    /**
     * @param array<string, mixed> $data
     * @param Collection<string, Attachment> $files
     */
    public function __construct(public string $url, public array $data, public Collection $files)
    {
    }

    public function handle(): void
    {
        $asMultipart = $this->files->isNotEmpty();

        $request = $asMultipart
            ? Http::asMultipart()
            : Http::asJson();

        /** @var PendingRequest $request */
        $request = $this->files->reduce(
            function ($request, Attachment $attachment, string $key) {
                //@phpstan-ignore-next-line
                return $request->attach($key, $attachment->contents(), $attachment->filename());
            },
            $request
        );

        // Apply proxy configuration if set
        if ($proxy = config('telegraph.http_proxy')) {
            $request->withOptions(['proxy' => $proxy]);
        }

        /** @phpstan-ignore-next-line  */
        $request->timeout(config('telegraph.http_timeout', 30))->connectTimeout(config('telegraph.http_connection_timeout', 10))->post($this->url, $this->data);
    }
}
