<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class OrderInfo implements Arrayable
{
    private ?string $name = null;
    private ?string $phoneNumber = null;
    private ?string $email = null;
    private ?ShippingAddress $shippingAddress = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     name?: string,
     *     phone_number?: string,
     *     email?: string,
     *     shipping_address?: array<string, mixed>,
     * } $data
     */
    public static function fromArray(array $data): OrderInfo
    {
        $orderInfo = new self();

        $orderInfo->name = $data['name'] ?? null;
        $orderInfo->phoneNumber = $data['phone_number'] ?? null;
        $orderInfo->email = $data['email'] ?? null;

        if (isset($data['shipping_address'])) {
            /* @phpstan-ignore-next-line  */
            $orderInfo->shippingAddress = ShippingAddress::fromArray($data['shipping_address']);
        }

        return $orderInfo;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function phoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function shippingAddress(): ?ShippingAddress
    {
        return $this->shippingAddress;
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'phone_number' => $this->phoneNumber,
            'email' => $this->email,
            'shipping_address' => $this->shippingAddress?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
