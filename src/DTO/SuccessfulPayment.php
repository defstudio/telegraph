<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class SuccessfulPayment implements Arrayable
{
    public string $currency;
    public int $totalAmount;
    public string $invoicePayload;
    public string $telegramPaymentChargeId;
    public string $providerPaymentChargeId;

    /**
     * @param array{
     *     id:int,
     *     currency:string,
     *     total_amount:int,
     *     invoice_payload:string,
     *     from:array<string, mixed>,
     *     telegram_payment_charge_id:string,
     *     provider_payment_charge_id:string
     * } $data
     */
    public static function fromArray(array $data): self
    {
        $query = new self();

        $query->currency = $data['currency'];
        $query->totalAmount = $data['total_amount'];
        $query->invoicePayload = $data['invoice_payload'];
        $query->telegramPaymentChargeId = $data['telegram_payment_charge_id'];
        $query->providerPaymentChargeId = $data['provider_payment_charge_id'];

        return $query;
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
            'invoice_payload' => $this->invoicePayload,
            'telegram_payment_charge_id' => $this->telegramPaymentChargeId,
            'provider_payment_charge_id' => $this->providerPaymentChargeId,
        ];
    }
}
