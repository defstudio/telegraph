<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class PreCheckoutQuery implements Arrayable
{
    /**
     * Unique query identifier
     */
    public string $id;

    /**
     * User who sent the query
     */
    public User $from;

    /**
     * Three-letter ISO 4217 currency code
     */
    public string $currency;

    /**
     * Total price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
     */
    public int $totalAmount;

    /**
     * Bot specified invoice payload
     */
    public string $invoicePayload;

    /**
     * @param array{id:string, currency:string, total_amount:int, invoice_payload:string, from:array<string, mixed>} $data
     */
    public static function fromArray(array $data): self
    {
        $query = new self();

        $query->id = $data['id'];

        $query->currency = $data['currency'];
        $query->totalAmount = $data['total_amount'];
        $query->invoicePayload = $data['invoice_payload'];

        /* @phpstan-ignore-next-line */
        $query->from = User::fromArray($data['from']);

        return $query;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
            'invoice_payload' => $this->invoicePayload,
            'from' => $this->from->toArray(),
        ];
    }
}
