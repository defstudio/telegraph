<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class SuccessfulPayment implements Arrayable
{
    private string $currency;
    private int $totalAmount;
    private string $invoicePayload;
    private ?int $subscriptionExpirationDate = null;
    private bool $isRecurring = false;
    private bool $isFirstRecurring = false;
    private ?string $shippingOptionId = null;
    private ?OrderInfo $orderInfo = null;
    private string $telegramPaymentChargeId;
    private string $providerPaymentChargeId;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     currency:string,
     *     total_amount:string,
     *     invoice_payload:string,
     *     subscription_expiration_date?:string,
     *     is_recurring:bool,
     *     is_first_recurring:bool,
     *     shipping_option_id?:string,
     *     order_info?:array<string,mixed>,
     *     telegram_payment_charge_id:string,
     *     provider_payment_charge_id:string,
     * } $data
     */
    public static function fromArray(array $data): SuccessfulPayment
    {

        $successfulPayment = new self();

        $successfulPayment->currency = $data['currency'];
        $successfulPayment->totalAmount = $data['total_amount'];
        $successfulPayment->isRecurring = $data['is_recurring'] ?? false;
        $successfulPayment->isFirstRecurring = $data['is_first_recurring'] ?? false;
        $successfulPayment->invoicePayload = $data['invoice_payload'];
        $successfulPayment->subscriptionExpirationDate = $data['subscription_expiration_date'] ?? null;
        $successfulPayment->shippingOptionId = $data['shipping_option_id'] ?? null;


        if (isset($data['order_info'])) {
            /* @phpstan-ignore-next-line */
            $successfulPayment->orderInfo = OrderInfo::fromArray($data['order_info']);
        }

        $successfulPayment->telegramPaymentChargeId = $data['telegram_payment_charge_id'];
        $successfulPayment->providerPaymentChargeId = $data['provider_payment_charge_id'];

        return $successfulPayment;
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

    public function subscriptionExpirationDate(): string
    {
        return $this->subscriptionExpirationDate;
    }

    public function isRecurring(): bool
    {
        return $this->isRecurring;
    }

    public function isFirstRecurring(): bool
    {
        return $this->isFirstRecurring;
    }

    public function shippingOptionId(): ?string
    {
        return $this->shippingOptionId;
    }

    public function orderInfo(): ?OrderInfo
    {
        return $this->orderInfo;
    }

    public function telegramPaymentChargeId(): string
    {
        return $this->telegramPaymentChargeId;
    }

    public function providerPaymentChargeId(): string
    {
        return $this->providerPaymentChargeId;
    }

    public function toArray(): array
    {
        return array_filter([
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
            'invoice_payload' => $this->invoicePayload,
            'subscription_expiration_date' => $this->subscriptionExpirationDate,
            'is_recurring' => $this->isRecurring,
            'is_first_recurring' => $this->isFirstRecurring,
            'shipping_option_id' => $this->shippingOptionId,
            'order_info' => $this->orderInfo?->toArray(),
            'telegram_payment_charge_id' => $this->telegramPaymentChargeId,
            'provider_payment_charge_id' => $this->providerPaymentChargeId,
        ], fn($value) => $value !== null);
    }
}
