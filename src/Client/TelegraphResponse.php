<?php

namespace DefStudio\Telegraph\Client;

use Illuminate\Http\Client\Response;

class TelegraphResponse extends Response
{
    public static function fromResponse(Response $response): TelegraphResponse
    {
        return new self($response->toPsrResponse());
    }

    public function telegraphOk(): bool
    {
        return parent::successful() && $this->json('ok');
    }

    public function telegraphError(): bool
    {
        return !$this->telegraphOk();
    }

    public function telegraphMessageId(): int|null
    {
        if (!$this->telegraphOk()) {
            return null;
        }

        /* @phpstan-ignore-next-line */
        return (int) $this->json('result.message_id');
    }

    public function dump(?string $key = null): static
    {
        dump($this->json($key));

        return $this;
    }

    /**
     * @return never-returns
     */
    public function dd(?string $key = null): void
    {
        dd($this->json($key));
    }
}
