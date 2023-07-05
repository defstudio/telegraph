<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @todo
 * https://github.com/WendellAdriel/laravel-validated-dto
 * https://core.telegram.org/bots/payments/currencies.json
 */
class LabeledPrice implements Arrayable
{
    /**
     * Portion label
     */
    public string $label;

    /**
     * Price of the product in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
     */
    public int $amount;

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'amount' => $this->amount,
        ];
    }
}
