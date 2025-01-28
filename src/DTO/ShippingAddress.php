<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class ShippingAddress implements Arrayable
{
    private string $countryCode;
    private string $state;
    private string $city;
    private string $streetLine1;
    private string $streetLine2;
    private string $postCode;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     country_code: string,
     *     state: string,
     *     city: string,
     *     street_line1: string,
     *     street_line2: string,
     *     post_code: string,
     * } $data
     */
    public static function fromArray(array $data): ShippingAddress
    {
        $shippingAddress = new self();

        $shippingAddress->countryCode = $data['country_code'];
        $shippingAddress->state = $data['state'];
        $shippingAddress->city = $data['city'];
        $shippingAddress->streetLine1 = $data['street_line1'];
        $shippingAddress->streetLine2 = $data['street_line2'];
        $shippingAddress->postCode = $data['post_code'];

        return $shippingAddress;
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function state(): int
    {
        return $this->state;
    }

    public function city(): ?string
    {
        return $this->city;
    }

    public function streetLine1(): ?string
    {
        return $this->streetLine1;
    }

    public function streetLine2(): ?string
    {
        return $this->streetLine1;
    }

    public function postCode(): ?int
    {
        return $this->postCode;
    }

    public function toArray(): array
    {
        return array_filter([
            'country_code' => $this->countryCode,
            'state' => $this->state,
            'city' => $this->city,
            'street_line1' => $this->streetLine1,
            'street_line2' => $this->streetLine2,
            'post_code' => $this->postCode,
        ], fn ($value) => $value !== null);
    }
}
