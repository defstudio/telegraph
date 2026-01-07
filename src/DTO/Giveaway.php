<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class Giveaway implements Arrayable
{
    /** @var Collection<array-key, Chat> */
    private Collection $chats;
    private int $winnersSelectionDate;
    private int $winnerCount;
    private bool $onlyNewMembers = false;
    private bool $hasPublicWinners = false;
    private ?string $prizeDescription;
    /** @var string[] */
    private array $countryCodes = [];
    private ?int $prizeStarCount;
    private ?int $premiumSubscriptionMonthCount;

    private function __construct()
    {
        $this->chats = Collection::empty();
    }

    /**
     * @param  array{
     *     chats: array<string, mixed>,
     *     winners_selection_date: int,
     *     winner_count: int,
     *     only_new_members: bool,
     *     has_public_winners: bool,
     *     prize_description?: string,
     *     country_codes?: array<string, string>,
     *     prize_star_count?: int,
     *     premium_subscription_month_count?: int
     * }  $data
     */
    public static function fromArray(array $data): Giveaway
    {
        $giveaway = new self();

        $giveaway->chats = collect($data['chats'] ?? [])->map(fn (array $chat) => Chat::fromArray($chat));
        $giveaway->winnersSelectionDate = $data['winners_selection_date'];
        $giveaway->winnerCount = $data['winner_count'];
        $giveaway->onlyNewMembers = $data['only_new_members'] ?? false;
        $giveaway->hasPublicWinners = $data['has_public_winners'] ?? false;
        $giveaway->prizeDescription = $data['prize_description'] ?? null;
        $giveaway->countryCodes = $data['country_codes'] ?? [];
        $giveaway->prizeStarCount = $data['prize_star_count'] ?? null;
        $giveaway->premiumSubscriptionMonthCount = $data['premium_subscription_month_count'] ?? null;


        return $giveaway;
    }

    /**
     * @return Collection<array-key, Chat>
     */
    public function chats(): Collection
    {
        return $this->chats;
    }

    public function winnersSelectionDate(): int
    {
        return $this->winnersSelectionDate;
    }

    public function winnerCount(): int
    {
        return $this->winnerCount;
    }

    public function onlyNewMembers(): bool
    {
        return $this->onlyNewMembers;
    }

    public function hasPublicWinners(): bool
    {
        return $this->hasPublicWinners;
    }

    public function prizeDescription(): ?string
    {
        return $this->prizeDescription;
    }

    /** @return string[] */
    public function countryCodes(): array
    {
        return $this->countryCodes;
    }

    public function prizeStarCount(): ?int
    {
        return $this->prizeStarCount;
    }

    public function premiumSubscriptionMonthCount(): ?int
    {
        return $this->premiumSubscriptionMonthCount;
    }

    public function toArray(): array
    {
        return array_filter([
            'chats' => $this->chats->toArray(),
            'winners_selection_date' => $this->winnersSelectionDate,
            'winner_count' => $this->winnerCount,
            'only_new_members' => $this->onlyNewMembers,
            'has_public_winners' => $this->hasPublicWinners,
            'prize_description' => $this->prizeDescription,
            'country_codes' => $this->countryCodes,
            'prize_star_count' => $this->prizeStarCount,
            'premium_subscription_month_count' => $this->premiumSubscriptionMonthCount,

        ], fn ($value) => $value !== null);
    }
}
