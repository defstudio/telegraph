<?php

/** @noinspection PhpUnused */
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\Client\TelegraphResponse;
use DefStudio\Telegraph\Concerns\AnswersInlineQueries;
use DefStudio\Telegraph\Concerns\CallTraitsMethods;
use DefStudio\Telegraph\Concerns\ComposesMessages;
use DefStudio\Telegraph\Concerns\CreatesScopedPayloads;
use DefStudio\Telegraph\Concerns\HasBotsAndChats;
use DefStudio\Telegraph\Concerns\HasEndpoints;
use DefStudio\Telegraph\Concerns\InteractsWithCommands;
use DefStudio\Telegraph\Concerns\InteractsWithPayments;
use DefStudio\Telegraph\Concerns\InteractsWithTelegram;
use DefStudio\Telegraph\Concerns\InteractsWithWebhooks;
use DefStudio\Telegraph\Concerns\InteractWithUsers;
use DefStudio\Telegraph\Concerns\ManagesKeyboards;
use DefStudio\Telegraph\Concerns\SendsAttachments;
use DefStudio\Telegraph\Concerns\StoresFiles;
use DefStudio\Telegraph\DTO\Attachment;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;

class Telegraph
{
    use CallTraitsMethods;
    use InteractsWithTelegram;
    use HasBotsAndChats;
    use ComposesMessages;
    use ManagesKeyboards;
    use InteractsWithWebhooks;
    use SendsAttachments;
    use StoresFiles;
    use AnswersInlineQueries;
    use CreatesScopedPayloads;
    use InteractWithUsers;
    use InteractsWithCommands;
    use InteractsWithPayments;
    use Conditionable;
    use HasEndpoints;


    public const PARSE_HTML = 'html';
    public const PARSE_MARKDOWN = 'markdown';
    public const PARSE_MARKDOWNV2 = 'MarkdownV2';

    protected const TELEGRAM_API_BASE_URL = 'https://api.telegram.org/bot';
    protected const TELEGRAM_API_FILE_BASE_URL = 'https://api.telegram.org/file/bot';


    /** @var array<string, mixed> */
    protected array $data = [];

    /** @var Collection<string, Attachment> */
    protected Collection $files;

    public function __construct()
    {
        $this->files = Collection::empty();
    }

    public function send(): TelegraphResponse
    {
        $response = $this->sendRequestToTelegram();

        return TelegraphResponse::fromResponse($response);
    }

    public function dispatch(?string $queue = null): PendingDispatch
    {
        return $this->dispatchRequestToTelegram($queue);
    }

    /**
     * @return never-returns
     */
    public function dd(): void
    {
        dd($this->toArray());
    }

    public function dump(): Telegraph
    {
        dump($this->toArray());

        return $this;
    }

    public function withEndpoint(string $endpoint): static
    {
        $telegraph = clone $this;

        $telegraph->endpoint = ltrim($endpoint, '/');

        return $telegraph;
    }

    public function withData(string $key, mixed $value): static
    {
        $telegraph = clone $this;

        //@phpstan-ignore-next-line
        data_set($telegraph->data, $key, $value);

        return $telegraph;
    }

    public function inThread(int $thread_id): static
    {
        $telegraph = clone $this;

        //@phpstan-ignore-next-line
        data_set($telegraph->data, 'message_thread_id', $thread_id);

        return $telegraph;
    }

    public function inBusiness(string $business_connection_id): static
    {
        $telegraph = clone $this;

        //@phpstan-ignore-next-line
        data_set($telegraph->data, 'business_connection_id', $business_connection_id);

        return $telegraph;
    }
}
