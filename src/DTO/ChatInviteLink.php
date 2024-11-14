<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\DTO;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|bool|array<string, mixed>>
 */
class ChatInviteLink implements Arrayable
{
    private string $inviteLink;
    private User $creator;
    private bool $createsJoinRequest;
    private bool $isPrimary;
    private bool $isRevoked;
    private ?string $name = null;
    private ?CarbonInterface $expireDate = null;
    private ?int $memberLimit = null;
    private ?int $pendingJoinRequestsCount = null;
    private ?int $subscriptionPeriod = null;
    private ?int $subscriptionPrice = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     creator: array<string, mixed>,
     *     invite_link: string,
     *     creates_join_request: bool,
     *     is_primary: bool,
     *     is_revoked: bool,
     *     name?: string,
     *     expire_date?: int,
     *     member_limit?: int,
     *     pending_join_requests_count?: int,
     *     subscription_period?: int,
     *     subscription_price?: int
     *  } $data
     */
    public static function fromArray(array $data): ChatInviteLink
    {
        $invite = new self();

        $invite->inviteLink = $data['invite_link'];

        $invite->creator = User::fromArray($data['creator']);

        $invite->createsJoinRequest = $data['creates_join_request'];

        $invite->isPrimary = $data['is_primary'];

        $invite->isRevoked = $data['is_revoked'];

        if (isset($data['name'])) {
            $invite->name = $data['name'];
        }

        if (isset($data['expire_date'])) {
            /* @phpstan-ignore-next-line */
            $invite->expireDate = Carbon::createFromTimestamp($data['expire_date']);
        }

        if (isset($data['member_limit'])) {
            $invite->memberLimit = $data['member_limit'];
        }

        if (isset($data['pending_join_requests_count'])) {
            $invite->pendingJoinRequestsCount = $data['pending_join_requests_count'];
        }

        if (isset($data['subscription_period'])) {
            $invite->subscriptionPeriod = $data['subscription_period'];
        }

        if (isset($data['subscription_price'])) {
            $invite->subscriptionPrice = $data['subscription_price'];
        }

        return $invite;
    }

    public function inviteLink(): string
    {
        return $this->inviteLink;
    }

    public function creator(): User
    {
        return $this->creator;
    }

    public function createsJoinRequest(): bool
    {
        return $this->createsJoinRequest;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function isRevoked(): bool
    {
        return $this->isRevoked;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function expireDate(): ?CarbonInterface
    {
        return $this->expireDate;
    }

    public function memberLimit(): ?int
    {
        return $this->memberLimit;
    }

    public function pendingJoinRequestsCount(): ?int
    {
        return $this->pendingJoinRequestsCount;
    }

    public function subscriptionPeriod(): ?int
    {
        return $this->subscriptionPeriod;
    }

    public function subscriptionPrice(): ?int
    {
        return $this->subscriptionPrice;
    }

    public function toArray(): array
    {
        return array_filter([
            'invite_link' => $this->inviteLink,
            'creator' => $this->creator->toArray(),
            'creates_join_request' => $this->createsJoinRequest,
            'is_primary' => $this->isPrimary,
            'is_revoked' => $this->isRevoked,
            'name' => $this->name,
            'expire_date' => $this->expireDate?->timestamp,
            'member_limit' => $this->memberLimit,
            'pending_join_requests_count' => $this->pendingJoinRequestsCount,
            'subscription_period' => $this->subscriptionPeriod,
            'subscription_price' => $this->subscriptionPrice,
        ], fn ($value) => $value !== null);
    }
}
