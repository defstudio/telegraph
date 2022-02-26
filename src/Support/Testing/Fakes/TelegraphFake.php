<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Telegraph;
use GuzzleHttp\Psr7\BufferStream;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\QueueFake;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class TelegraphFake extends Telegraph
{
    /** @var array<int, mixed[]> */
    private array $sentMessages = [];

    /**
     * @param array<string, array<mixed>> $replies
     */
    public function __construct(private array $replies = [])
    {
        parent::__construct();
    }

    protected function dispatchRequestToTelegram(string $queue = null): PendingDispatch
    {
        $this->sentMessages[] = $this->messageToArray();

        if (!Queue::getFacadeRoot() instanceof QueueFake) {
            Queue::fake();
        }

        return parent::dispatchRequestToTelegram($queue);
    }

    /**
     * @return array<string, mixed>
     * @throws TelegraphException
     */
    protected function messageToArray(): array
    {
        return [
            'url' => $this->getUrl(),
            'endpoint' => $this->endpoint ?? null,
            'data' => $this->data ?? null,
            'bot_token' => $this->getBotIfAvailable()->token ?? null,
            'chat_id' => $this->getChatIfAvailable()->id ?? null,
            'message' => $this->message ?? null,
            'keyboard' => $this->keyboard ?? null,
            'parse_mode' => $this->parseMode ?? null,
        ];
    }

    protected function sendRequestToTelegram(): Response
    {
        $this->sentMessages[] = $this->messageToArray();

        $messageClass = new class () implements MessageInterface {
            /**
             * @param array<mixed> $reply
             */
            public function __construct(private array $reply = [])
            {
            }

            public function getProtocolVersion(): string
            {
                return "";
            }

            public function withProtocolVersion($version): static
            {
                return $this;
            }

            public function getHeaders(): array
            {
                return [];
            }

            public function hasHeader($name): bool
            {
                return false;
            }

            public function getHeader($name): array
            {
                return [];
            }

            public function getHeaderLine($name): string
            {
                return "";
            }

            public function withHeader($name, $value): static
            {
                return $this;
            }

            public function withAddedHeader($name, $value): static
            {
                return $this;
            }

            public function withoutHeader($name): static
            {
                return $this;
            }

            public function getBody(): StreamInterface
            {
                $buffer = new BufferStream();

                /** @phpstan-ignore-next-line */
                $buffer->write(json_encode($this->reply));

                return $buffer;
            }

            public function withBody(StreamInterface $body): static
            {
                return $this;
            }
        };

        $response = $this->replies[$this->endpoint] ?? ['ok' => true];

        return new Response(new $messageClass($response));
    }

    /**
     * @param array<string, string> $data
     */
    public function assertSentData(string $endpoint, array $data = [], bool $exact = true): void
    {
        $foundMessages = collect($this->sentMessages);

        $foundMessages = $foundMessages
            ->filter(fn (array $message): bool => $message['endpoint'] == $endpoint)
            ->filter(function (array $message) use ($data, $exact): bool {
                foreach ($data as $key => $value) {
                    /** @var array<string, string> $data */
                    $data = $message['data'];
                    if (!Arr::has($data, $key)) {
                        return false;
                    }

                    if ($exact) {
                        if ($value != $data[$key]) {
                            return false;
                        }
                    } else {
                        if (!Str::of($data[$key])->contains($value)) {
                            return false;
                        }
                    }
                }

                return true;
            });


        if ($data == null) {
            $errorMessage = sprintf("Failed to assert that a request was sent to [%s] endpoint (sent %d requests so far)", $endpoint, count($this->sentMessages));
        } else {
            $errorMessage = sprintf("Failed to assert that a request was sent to [%s] endpoint with the given data (sent %d requests so far)", $endpoint, count($this->sentMessages));
        }

        Assert::assertNotEmpty($foundMessages->toArray(), $errorMessage);
    }

    public function assertSent(string $message, bool $exact = true): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_MESSAGE, [
            'text' => $message,
        ], $exact);
    }

    public function assertNothingSent(): void
    {
        Assert::assertEmpty($this->sentMessages, sprintf("Failed to assert that no request were sent (sent %d requests so far)", count($this->sentMessages)));
    }

    public function assertRegisteredWebhook(): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_SET_WEBHOOK);
    }

    public function assertRequestedWebhookDebugInfo(): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_GET_WEBHOOK_DEBUG_INFO);
    }

    public function assertRepliedWebhook(string $message): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_ANSWER_WEBHOOK, [
            'text' => $message,
        ]);
    }

    public function dumpSentData(): void
    {
        dump($this->sentMessages);
    }
}
