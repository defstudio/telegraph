<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|null|array>
 */
class SharedUser implements Arrayable
{
    public int $userId;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $username = null;
    /** @var Photo[]|null */
    public ?array $photo = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     user_id: int,
     *     first_name?: string,
     *     last_name?: string,
     *     username?: string,
     *     photo?: array<array<string, mixed>>
     * } $data
     */
    public static function fromArray(array $data): SharedUser
    {
        $userShared = new self();

        $userShared->userId = $data['user_id'];
        $userShared->firstName = $data['first_name'] ?? null;
        $userShared->lastName = $data['last_name'] ?? null;
        $userShared->username = $data['username'] ?? null;

        if (isset($data['photo'])) {
            $userShared->photo = array_map(
                fn(array $photoData) => Photo::fromArray($photoData),
                $data['photo']
            );
        }

        return $userShared;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function firstName(): ?string
    {
        return $this->firstName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }

    public function username(): ?string
    {
        return $this->username;
    }

    /**
     * @return PhotoSize[]|null
     */
    public function photo(): ?array
    {
        return $this->photo;
    }

    public function toArray(): array
    {
        return array_filter([
            'user_id' => $this->userId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'photo' => $this->photo ? array_map(fn(Photo $photo) => $photo->toArray(), $this->photo) : null,
        ], fn($value) => $value !== null);
    }
}
