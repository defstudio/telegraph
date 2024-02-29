<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class ChatMember implements Arrayable
{
    public const STATUS_CREATOR = 'creator';
    public const STATUS_ADMINISTRATOR = 'administrator';
    public const STATUS_MEMBER = 'member';
    public const STATUS_RESTRICTED = 'restricted';
    public const STATUS_LEFT = 'left';
    public const STATUS_KICKED = 'kicked';

    private string $status;
    private User $user;
    private bool $isAnonymous;
    private string $custom_title;
    private bool $is_member;
    private ?int $until_date;

    private function __construct()
    {
    }

    /**
     * @param array{status:string, user:array<string, mixed>, is_anonymous?:bool, custom_title?:string, is_member?:bool, until_date?:int} $data
     */
    public static function fromArray(array $data): ChatMember
    {
        $member = new self();

        $member->status = $data['status'];

        /* @phpstan-ignore-next-line */
        $member->user = User::fromArray($data['user']);

        $member->isAnonymous = $data['is_anonymous'] ?? false;

        $member->custom_title = $data['custom_title'] ?? '';

        $member->is_member = $data['is_member'] ?? false;

        $member->until_date = $data['until_date'] ?? null;

        return $member;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function custom_title(): string
    {
        return $this->custom_title;
    }

    public function is_member(): bool
    {
        return $this->is_member;
    }

    public function until_date(): ?int
    {
        return $this->until_date;
    }

    public function toArray(): array
    {
        return array_filter([
            'status' => $this->status,
            'user' => $this->user->toArray(),
            'is_anonymous' => $this->isAnonymous,
            'custom_title' => $this->custom_title,
            'is_member' => $this->is_member,
            'until_date' => $this->until_date,
        ], fn ($value) => $value !== null);
    }
}
