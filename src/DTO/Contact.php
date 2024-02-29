<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Contact implements Arrayable
{
    private string $phone_number;
    private string $first_name;
    private ?string $last_name;
    private ?int $user_id = null;
    private ?string $vcard = null;

    private function __construct()
    {
    }

    /**
     * @param array{phone_number: string, first_name: string, last_name?: string, user_id?: int, vcard?: string} $data
     */
    public static function fromArray(array $data): Contact
    {
        $contact = new self();

        $contact->phone_number = $data['phone_number'];
        $contact->first_name = $data['first_name'];
        $contact->last_name = $data['last_name'] ?? null;
        $contact->user_id = $data['user_id'] ?? null;
        $contact->vcard = $data['vcard'] ?? null;

        return $contact;
    }

    public function phoneNumber(): string
    {
        return $this->phone_number;
    }

    public function firstName(): string
    {
        return $this->first_name;
    }

    public function lastName(): ?string
    {
        return $this->last_name;
    }

    public function userId(): ?int
    {
        return $this->user_id;
    }

    public function vcard(): ?string
    {
        return $this->vcard;
    }

    public function toArray(): array
    {
        return array_filter([
            'phone_number' => $this->phone_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'user_id' => $this->user_id,
            'vcard' => $this->vcard,
        ], fn ($value) => $value !== null);
    }
}
