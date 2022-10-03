<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Contracts\Downloadable;
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

trait FakesRequests
{
    /** @var array<int, mixed[]> */
    protected static array $sentMessages = [];

    /** @var array<int, string> */
    protected static array $downloadedFiles = [];

    protected array $replies = [];

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
            Telegraph::ENDPOINT_GET_CHAT_INFO => [
                'ok' => true,
                'result' => [
                    'id' => 42,
                    'type' => 'group',
                    'title' => 'foo',
                    'description' => 'bar',
                    'has_private_forwards' => true,
                    'join_by_request' => true,
                    'has_protected_content' => true,
                ],
            ],
            Telegraph::ENDPOINT_GET_CHAT_MEMBER_COUNT => [
                'ok' => true,
                'result' => 1,
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
    public static function assertSentData(string $endpoint, array $data = [], bool $exact = true): void
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

    public function dumpSentData(): void
    {
        dump(self::$sentMessages);
    }
}
