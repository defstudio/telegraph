<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Payments;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Exceptions\InvoiceException;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Validator;

class TelegraphInvoicePayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function invoice(string $title): static
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_INVOICE;

        $telegraph->data['title'] = $title;

        $telegraph->data['payload'] = 'created by Telegraph';

        $telegraph->data['provider_token'] = config('telegraph.payments.provider_token');

        $telegraph->data['prices'] = [];

        return $telegraph;
    }

    public function link(): static
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_CREATE_INVOICE_LINK;

        return $telegraph;
    }

    public function description(string $description): static
    {
        $telegraph = clone $this;

        $telegraph->data['description'] = $description;

        return $telegraph;
    }

    public function payload(string $payload): static
    {
        $telegraph = clone $this;

        $telegraph->data['payload'] = $payload;

        return $telegraph;
    }

    public function currency(string $currency): static
    {
        $telegraph = clone $this;

        $telegraph->data['currency'] = $currency;

        return $telegraph;
    }

    public function providerToken(string $providerToken): static
    {
        $telegraph = clone $this;

        $telegraph->data['provider_token'] = $providerToken;

        return $telegraph;
    }

    public function addItem(string $label, int $amount): static
    {
        $telegraph = clone $this;

        /** @phpstan-ignore-next-line */
        $telegraph->data['prices'][] = [
            'label' => $label,
            'amount' => $amount,
        ];

        return $telegraph;
    }

    public function maxTip(int $amount): static
    {
        $telegraph = clone $this;

        $telegraph->data['max_tip_amount'] = $amount;

        return $telegraph;
    }

    /**
     * @param int[] $amounts
     */
    public function suggestedTips(array $amounts): static
    {
        $telegraph = clone $this;

        $telegraph->data['suggested_tip_amounts'] = $amounts;

        return $telegraph;
    }

    public function startParameter(string $value): static
    {
        $telegraph = clone $this;

        $telegraph->data['start_parameter'] = $value;

        return $telegraph;
    }

    /**
     * @param array<string> $data
     */
    public function providerData(array $data): static
    {
        $telegraph = clone $this;

        $telegraph->data['provider_data'] = json_encode($data);

        return $telegraph;
    }

    public function image(string $url, int $sizeInBytes = null, int $width = null, int $height = null): static
    {
        $telegraph = clone $this;

        $telegraph->data['photo_url'] = $url;

        if ($sizeInBytes !== null) {
            $telegraph->data['photo_size'] = $sizeInBytes;
        }

        if ($width !== null) {
            $telegraph->data['photo_width'] = $width;
        }

        if ($height !== null) {
            $telegraph->data['photo_height'] = $height;
        }

        return $telegraph;
    }

    public function needName(bool $needed = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['need_name'] = $needed;

        return $telegraph;
    }

    public function needPhoneNumber(bool $needed = true, bool $sendToProvider = false): static
    {
        $telegraph = clone $this;

        $telegraph->data['need_phone_number'] = $needed;
        $telegraph->data['send_phone_number_to_provider'] = $sendToProvider;

        return $telegraph;
    }

    public function needEmail(bool $needed = true, bool $sendToProvider = false): static
    {
        $telegraph = clone $this;

        $telegraph->data['need_email'] = $needed;
        $telegraph->data['send_email_to_provider'] = $sendToProvider;

        return $telegraph;
    }

    public function needShippingAddress(bool $needed = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['need_shipping_address'] = $needed;

        return $telegraph;
    }

    public function flexible(bool $flexible = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['is_flexible'] = $flexible;

        return $telegraph;
    }

    protected function prepareData(): array
    {
        $data = parent::prepareData();

        if (empty($data['chat_id']) && $this->endpoint === self::ENDPOINT_SEND_INVOICE) {
            $data['chat_id'] = $this->getChatId();
        }

        $validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:32', 'min:1'],
            'description' => ['required', 'string', 'max:255', 'min:1'],
            'payload' => ['required', 'string', 'max:128', 'min:1'],
            'currency' => ['required', 'string', 'max:3', 'min:3'],
            'provider_token' => ['nullable', 'string'],
            'prices' => ['required', 'array'],
            'prices.*.label' => ['required', 'string'],
            'prices.*.amount' => ['required', 'integer'],
            'max_tip_amount' => ['nullable', 'integer'],
            'suggested_tip_amounts' => ['nullable', 'array'],
            'suggested_tip_amounts.*' => ['integer', 'min:1'],
            'start_parameter' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            throw InvoiceException::validationError($validator->messages());
        }

        return $data;
    }
}
