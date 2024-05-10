<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Arr;
use DefStudio\Telegraph\DTO\Invoice;

/**
 * @mixin Telegraph
 */
trait InteractWithPayment
{
    public function invoice(Invoice $invoice): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_INVOICE;
        $data = $telegraph->data;
        Arr::set($data, 'chat_id', $this->getChatId());
        Arr::set($data, 'title', $invoice->getTitle());
        Arr::set($data, 'description', $invoice->getDescription());
        Arr::set($data, 'payload', $invoice->getPayload());
        Arr::set($data, 'provider_token', $invoice->getProviderToken());
        Arr::set($data, 'currency', $invoice->getCurrency());
        Arr::set($data, 'prices', $invoice->getPrices());
        $telegraph->data = $data;

        return $telegraph;
    }
}
