<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|null|array>
 */
class SharedUsers implements Arrayable
{
    public int $requestId;
    /** @var SharedUser[] */
    public array $users;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     request_id: int,
     *     users: array<array<string, mixed>>
     * } $data
     */
    public static function fromArray(array $data): SharedUsers
    {
        $sharedUsers = new self();

        $sharedUsers->requestId = $data['request_id'];
        $sharedUsers->users = array_map(
            fn(array $userData) => SharedUser::fromArray($userData),
            $data['users']
        );

        return $sharedUsers;
    }

    public function requestId(): int
    {
        return $this->requestId;
    }

    /**
     * @return SharedUser[]
     */
    public function users(): array
    {
        return $this->users;
    }

    public function toArray(): array
    {
        return [
            'request_id' => $this->requestId,
            'users' => array_map(fn(SharedUser $user) => $user->toArray(), $this->users),
        ];
    }
}
