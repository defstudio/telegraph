<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class Invoice implements Arrayable
{
    public string $title;

    public string $description;

    public string $startParameter;

    public string $currency;

    public int $totalAmount;

    public function __construct()
    {
    }

    /**
     * @param array{
     *     title: string,
     *     description: string,
     *     start_parameter: string,
     *     currency: string,
     *     total_amount: int
     * } $data
     */
    public static function fromArray(array $data): Invoice
    {
        $invoice = new self();

        $invoice->title = $data['title'];
        $invoice->description = $data['description'];
        $invoice->startParameter = $data['start_parameter'];
        $invoice->currency = $data['currency'];
        $invoice->totalAmount = $data['total_amount'];

        return $invoice;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function startParameter(): string
    {
        return $this->startParameter;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function totalAmount(): int
    {
        return $this->totalAmount;
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'start_parameter' => $this->startParameter,
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
        ], fn ($value) => $value !== null);
    }
}
