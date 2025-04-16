<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class RefundedPayment implements Arrayable
{
    private string $currency;
    private int $totalAmount;
    private string $invoicePayload;
    private string $telegramPaymentChargeId;
    private ?string $providerPaymentChargeId;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     currency:string,
     *     total_amount:int,
     *     invoice_payload:string,
     *     telegram_payment_charge_id:string,
     *     provider_payment_charge_id:string,
     * } $data
     */
    public static function fromArray(array $data): RefundedPayment
    {

        $refundedPayment = new self();

        $refundedPayment->currency = $data['currency'];
        $refundedPayment->totalAmount = $data['total_amount'];
        $refundedPayment->invoicePayload = $data['invoice_payload'];
        $refundedPayment->telegramPaymentChargeId = $data['telegram_payment_charge_id'];
        $refundedPayment->providerPaymentChargeId = $data['provider_payment_charge_id'] ?? null;

        return $refundedPayment;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function totalAmount(): int
    {
        return $this->totalAmount;
    }

    public function invoicePayload(): string
    {
        return $this->invoicePayload;
    }

    public function telegramPaymentChargeId(): string
    {
        return $this->telegramPaymentChargeId;
    }

    public function providerPaymentChargeId(): ?string
    {
        return $this->providerPaymentChargeId;
    }

    public function toArray(): array
    {
        return array_filter([
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
            'invoice_payload' => $this->invoicePayload,
            'telegram_payment_charge_id' => $this->telegramPaymentChargeId,
            'provider_payment_charge_id' => $this->providerPaymentChargeId,
        ], fn ($value) => $value !== null);
    }
}
