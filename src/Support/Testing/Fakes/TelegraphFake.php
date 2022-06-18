<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Contracts\Downloadable;
use DefStudio\Telegraph\DTO\Attachment;
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
    private static array $sentMessages = [];

    /** @var array<int, string> */
    private static array $downloadedFiles = [];

    /**
     * @param array<string, array<mixed>> $replies
     */
    public function __construct(private array $replies = [])
    {
        parent::__construct();
    }

    public static function reset(): void
    {
        self::$sentMessages = [];
    }

    protected function dispatchRequestToTelegram(string $queue = null): PendingDispatch
    {
        self::$sentMessages[] = $this->messageToArray();

        if (!Queue::getFacadeRoot() instanceof QueueFake) {
            Queue::fake();
        }

        return parent::dispatchRequestToTelegram($queue);
    }

    /**
     * @return array<string, mixed>
     */
    protected function messageToArray(): array
    {
        return [
            'url' => $this->getApiUrl(),
            'endpoint' => $this->endpoint ?? null,
            'data' => $this->prepareData(),
            'files' => $this->files,
            'bot_token' => $this->getBotIfAvailable()->token ?? null,
            'chat_id' => $this->getChatIfAvailable()->id ?? null,
            'message' => $this->data['text'] ?? null,
            'parse_mode' => $this->data['parse_mode'] ?? null,
        ];
    }

    protected function sendRequestToTelegram(): Response
    {
        self::$sentMessages[] = $this->messageToArray();

        $messageClass = new class () implements MessageInterface {
            /**
             * @param array<string, mixed> $reply
             */
            public function __construct(private array|string $reply = [])
            {
            }

            public function getStatusCode(): int
            {
                return 200;
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

        $response = $this->replies[$this->endpoint] ?? match ($this->endpoint) {
            Telegraph::ENDPOINT_MESSAGE => [
                    'ok' => true,
                    'result' => [
                        'message_id' => rand(1, 99999),
                        'sender_chat' => [
                            'id' => $this->getChatIfAvailable()?->chat_id ?? -rand(1, 99999),
                            'title' => 'Test Chat',
                            'type' => 'channel',
                        ],
                        'date' => now()->timestamp,
                        'text' => $this->data['text'],
                    ],
                ],
                Telegraph::ENDPOINT_GET_BOT_INFO => [
                    'ok' => true,
                    'result' => [
                        'id' => 42,
                        'is_bot' => true,
                        'first_name' => 'telegraph-test',
                        'username' => 'test_bot',
                        'can_join_groups' => true,
                        'can_read_all_group_messages' => false,
                        'supports_inline_queries' => false,
                    ],
                ],
                Telegraph::ENDPOINT_GET_BOT_UPDATES => [
                    'ok' => true,
                    'result' => [
                        [
                            'update_id' => 123456,
                            'message' => [
                                'message_id' => 42,
                                'from' => [
                                    'id' => 444,
                                    'is_bot' => false,
                                    'first_name' => 'John',
                                    'last_name' => 'Smith',
                                    'username' => 'john_smith',
                                    'language_code' => 'en',
                                ],
                                'chat' => [
                                    'id' => 987654,
                                    'first_name' => 'John',
                                    'last_name' => 'Smith',
                                    'username' => 'john_smith',
                                    'type' => 'private',
                                ],
                                'date' => 1646516736,
                                'text' => '/start',
                                'entities' => [
                                    [
                                        'offset' => 0,
                                        'length' => 6,
                                        'type' => 'bot_command',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'update_id' => 123457,
                            'message' => [
                                'message_id' => 99,
                                'from' => [
                                    'id' => 8974,
                                    'is_bot' => true,
                                    'first_name' => 'Test Bot',
                                    'username' => 'test_bot',
                                    'can_join_groups' => true,
                                    'can_read_all_group_messages' => false,
                                    'supports_inline_queries' => false,
                                ],
                                'chat' => [
                                    'id' => -987455499,
                                    'title' => 'Bot Test Chat',
                                    'type' => 'group',
                                ],
                                'date' => 1646519736,
                                'text' => 'Hello world!',
                                'entities' => [],
                            ],
                        ],
                    ],
                ],
                default => [
                    'ok' => true,
                ],
        };

        return new Response(new $messageClass($response));
    }

    public function store(Downloadable|string $downloadable, string $path, string $filename = null): string
    {
        $fileId = is_string($downloadable) ? $downloadable : $downloadable->id();

        self::$downloadedFiles[] = $fileId;

        return $path . "/" . ($filename ?? 'missing_name.jpg');
    }

    /**
     * @param array<string, string> $data
     */
    public function assertSentData(string $endpoint, array $data = [], bool $exact = true): void
    {
        $foundMessages = collect(self::$sentMessages);

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
            $errorMessage = sprintf("Failed to assert that a request was sent to [%s] endpoint (sent %d requests so far)", $endpoint, count(self::$sentMessages));
        } else {
            $errorMessage = sprintf("Failed to assert that a request was sent to [%s] endpoint with the given data (sent %d requests so far)", $endpoint, count(self::$sentMessages));
        }

        Assert::assertNotEmpty($foundMessages->toArray(), $errorMessage);
    }

    /**
     * @param array<string, Attachment> $expectedFiles
     */
    public function assertSentFiles(string $endpoint, array $expectedFiles = []): void
    {
        $foundMessages = collect(self::$sentMessages);

        $foundMessages = $foundMessages
            ->filter(fn (array $message): bool => $message['endpoint'] == $endpoint)
            ->filter(function (array $message) use ($expectedFiles): bool {
                foreach ($expectedFiles as $key => $expectedFile) {
                    /** @var array<string, Attachment> $sentFiles */
                    $sentFiles = $message['files'];

                    if (!Arr::has($sentFiles, $key)) {
                        return false;
                    }

                    if ($expectedFile->filename() !== $sentFiles[$key]->filename()) {
                        return false;
                    }

                    if ($expectedFile->contents() !== $sentFiles[$key]->contents()) {
                        return false;
                    }
                }

                return true;
            });


        if ($foundMessages == null) {
            $errorMessage = sprintf("Failed to assert that a request was sent to [%s] endpoint (sent %d requests so far)", $endpoint, count(self::$sentMessages));
        } else {
            $errorMessage = sprintf("Failed to assert that a request was sent to [%s] endpoint with the given files (sent %d requests so far)", $endpoint, count(self::$sentMessages));
        }

        Assert::assertNotEmpty($foundMessages->toArray(), $errorMessage);
    }

    public function assertStoredFile(string $fileId): void
    {
        $downloadedFiles = collect(self::$downloadedFiles)
            ->filter(fn ($downloadedFileId) => $fileId === $downloadedFileId);

        Assert::assertNotEmpty($downloadedFiles->toArray(), sprintf("Failed to assert that a file with id [%s] was stored (%d files stored so fare)", $fileId, count(self::$downloadedFiles)));
    }

    public function assertSent(string $message, bool $exact = true): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_MESSAGE, [
            'text' => $message,
        ], $exact);
    }

    public function assertNothingSent(): void
    {
        Assert::assertEmpty(self::$sentMessages, sprintf("Failed to assert that no request were sent (sent %d requests so far)", count(self::$sentMessages)));
    }

    public function assertRegisteredWebhook(): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_SET_WEBHOOK);
    }

    public function assertUnregisteredWebhook(): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_UNSET_WEBHOOK);
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
        dump(self::$sentMessages);
    }
}
