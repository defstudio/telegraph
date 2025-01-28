<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class PreCheckoutQuery implements Arrayable
{
    private int $id;
    private User $from;
    private string $currency;
    private int $totalAmount;
    private string $invoicePayload;
    private ?string $shippingOptionId = null;
    private ?OrderInfo $orderInfo = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     id:int,
     *     from:array<string, mixed>,
     *     currency:string,
     *     total_amount:string,
     *     invoice_payload:string,
     *     shipping_option_id?:string,
     *     order_info?:array<string,mixed>
     * } $data
     */
    public static function fromArray(array $data): PreCheckoutQuery
    {
        $preCheckoutQuery = new self();

        $preCheckoutQuery->id = $data['id'];

        /* @phpstan-ignore-next-line */
        $preCheckoutQuery->from = User::fromArray($data['from']);

        $preCheckoutQuery->currency = $data['currency'];
        $preCheckoutQuery->totalAmount = $data['total_amount'];
        $preCheckoutQuery->invoicePayload = $data['invoice_payload'];
        $preCheckoutQuery->shippingOptionId = $data['shipping_option_id'] ?? null;


        if (isset($data['order_info'])) {
            /* @phpstan-ignore-next-line */
            $preCheckoutQuery->orderInfo = OrderInfo::fromArray($data['order_info']);
        }

        return $preCheckoutQuery;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function from(): User
    {
        return $this->from;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function totalAmount(): string
    {
        return $this->totalAmount;
    }

    public function invoicePayload(): string
    {
        return $this->invoicePayload;
    }

    public function shippingOptionId(): ?string
    {
        return $this->shippingOptionId;
    }

    public function orderInfo(): ?OrderInfo
    {
        return $this->orderInfo;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'from' => $this->from->toArray(),
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
            'invoice_payload' => $this->invoicePayload,
            'shipping_option_id' => $this->shippingOptionId,
            'order_info' => $this->orderInfo?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
