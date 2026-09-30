<?php

namespace DefStudio\Telegraph\DTO\RichBlock\RichBlockElements;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\Factories\RichBlockFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockListItem implements Arrayable
{
    private string $label;
    /** @var Collection<array-key,RichBlockItem> */
    private Collection $blocks;
    private bool $hasCheckbox = false;
    private bool $isChecked = false;
    private ?int $value = null;
    private ?string $type = null;

    public function __construct()
    {
        $this->blocks = Collection::empty();
    }

    /**
     * @param  array{
     *     label: string,
     *     blocks: array<array-key, Object>,
     *     has_checkbox?: bool,
     *     is_checked?: bool,
     *     value?: int,
     *     type?: string
     * }  $data
     */
    public static function fromArray(array $data): RichBlockListItem
    {
        $richBlockListItem = new self();

        /* @phpstan-ignore-next-line */
        $richBlockListItem->blocks = collect($data['blocks'])->map(fn (array $blockData) => app(RichBlockFactory::class)->fromArray($blockData));

        $richBlockListItem->label = $data['label'];
        $richBlockListItem->type = $data['type'] ?? null;
        $richBlockListItem->value = $data['value'] ?? null;
        $richBlockListItem->hasCheckbox = $data['has_checkbox'] ?? false;
        $richBlockListItem->isChecked = $data['is_checked'] ?? false;

        return $richBlockListItem;
    }

    public function label(): string
    {
        return $this->label;
    }

    /**
     * @return Collection<array-key, RichBlockItem>
     */
    public function blocks(): Collection
    {
        return $this->blocks;
    }

    public function hasCheckbox(): bool
    {
        return $this->hasCheckbox;
    }

    public function isChecked(): bool
    {
        return $this->isChecked;
    }

    public function value(): ?int
    {
        return $this->value;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function toArray(): array
    {
        return array_filter([
            'label' => $this->label,
            'blocks' => $this->blocks->toArray(),
            'has_checkbox' => $this->hasCheckbox,
            'is_checked' => $this->isChecked,
            'value' => $this->value,
            'type' => $this->type,
        ], fn ($value) => $value !== null);
    }
}
