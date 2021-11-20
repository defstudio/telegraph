<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\LaravelTelegraph\Support\Testing\Fakes;

use DefStudio\LaravelTelegraph\LaravelTelegraph;
use Illuminate\Http\Client\Response;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class LaravelTelegraphFake extends LaravelTelegraph
{
    /** @var array<int, mixed> */
    private array $messages = [];

    protected function sendRequestToTelegram(): Response
    {
        $this->messages[] = [
            'url' => $this->getUrl(),
            'endpoint' => $this->endpoint ?? null,
            'data' => $this->data ?? null,
            'bot_token' => $this->botToken ?? null,
            'chat_id' => $this->chatId ?? null,
            'message' => $this->message ?? null,
            'keyboard' => $this->keyboard ?? null,
            'parse_mode' => $this->parseMode ?? null,
        ];


        $messageClass = new class () implements MessageInterface {
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

                /** @noinspection PhpIncompatibleReturnTypeInspection */
                return '';                /** @phpstan-ignore-line  */
            }

            public function withBody(StreamInterface $body): static
            {
                return $this;
            }
        };

        return new Response(new $messageClass());
    }
}
