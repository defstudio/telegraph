<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Concerns\HasStorage;
use DefStudio\Telegraph\Contracts\Storable;
use Illuminate\Contracts\Support\Arrayable;

class User implements Arrayable, Storable
{
    use HasStorage;

    private int $id;
    private bool $isBot;
    private string $firstName;
    private string $lastName;
    private string $username;
    private string $languageCode;

    private function __construct()
    {
    }

    /**
     * @param array{id:int, is_bot:bool, first_name?:string, last_name?:string, username?:string, language_code?:string} $data
     */
    public static function fromArray(array $data): User
    {
        $user = new self();

        $user->id = $data['id'];
        $user->isBot = $data['is_bot'];

        $user->firstName = $data['first_name'] ?? '';
        $user->lastName = $data['last_name'] ?? '';
        $user->username = $data['username'] ?? '';
        $user->languageCode = $data['language_code'] ?? '';

        return $user;
    }

    public function storageKey(): string|int
    {
        return $this->id;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function languageCode(): string
    {
        return $this->languageCode;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'is_bot' => $this->isBot,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'language_code' => $this->languageCode,
        ], fn ($value) => $value !== null);
    }
}
