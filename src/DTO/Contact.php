<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Contact implements Arrayable
{
    private string $phone_number;
    private string $first_name;
    private string $last_name;
    private ?int $user_id = null;
    private ?string $vcard = null;

    private function __construct()
    {
    }

    public static function fromArray(array $data): Contact
    {
        $contact = new self();

        $contact->phone_number = $data['phone_number'];
        $contact->first_name = $data['first_name'];
        $contact->last_name = $data['last_name'];
        $contact->user_id = $data['user_id'] ?? null;
        $contact->vcard = $data['vcard'] ?? null;

        return $contact;
    }

    public function phone_number(): string
    {
        return $this->phone_number;
    }

    public function first_name(): string
    {
        return $this->first_name;
    }

    public function last_name(): string
    {
        return $this->last_name;
    }

    public function user_id(): ?int
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
        ]);
    }
}
