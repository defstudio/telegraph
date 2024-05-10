<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Invoice implements Arrayable
{
    private string $title;

    private string $description;

    private string $payload;

    private string $providerToken;

    /**
     * @link https://core.telegram.org/bots/payments#supported-currencies
     */
    private string $currency;

    /**
     * @var array<string, string|int>
     *
     * @link https://core.telegram.org/bots/api#labeledprice
     */
    private array $prices;

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     payload:string,
     *     provider_token:string,
     *     currency:string,
     *     prices:array{label:string, amount:int}
     * } $data
     */
    public static function fromArray(array $data): static
    {
        $invoice = new static();

        $invoice->title = Arr::get($data, 'title');
        $invoice->description = Arr::get($data, 'description');
        $invoice->payload = Arr::get($data, 'payload');
        $invoice->providerToken = Arr::get($data, 'provider_token');
        $invoice->currency = Arr::get($data, 'currency');
        $invoice->prices = Arr::get($data, 'prices');

        return $invoice;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function getProviderToken(): string
    {
        return $this->providerToken;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'payload' => $this->payload,
            'provider_token' => $this->providerToken,
            'currency' => $this->currency,
            'prices' => $this->prices,
        ];
    }
}
