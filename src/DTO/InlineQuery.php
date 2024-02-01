<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class InlineQuery implements Arrayable
{
    private string $id;
    private string $query;
    private User $from;
    private string $offset;
    private string $chatType;
    private ?Location $location = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     id: string,
     *     from: array<string, mixed>,
     *     query: string|null,
     *     offset: string|null,
     *     chat_type: string,
     *     location?: array<string, mixed>
     * } $data
     */
    public static function fromArray(array $data): InlineQuery
    {
        $inlineQuery = new self();

        $inlineQuery->id = $data['id'];

        /** @phpstan-ignore-next-line  */
        $inlineQuery->from = User::fromArray($data['from']);

        $inlineQuery->query = $data['query'] ?? '';
        $inlineQuery->offset = $data['offset'] ?? '';
        $inlineQuery->chatType = $data['chat_type'];

        if (isset($data['location'])) {
            /** @phpstan-ignore-next-line */
            $inlineQuery->location = Location::fromArray($data['location']);
        }

        return $inlineQuery;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function query(): string
    {
        return $this->query;
    }

    public function from(): User
    {
        return $this->from;
    }

    public function offset(): string
    {
        return $this->offset;
    }

    public function chatType(): string
    {
        return $this->chatType;
    }

    public function location(): ?Location
    {
        return $this->location;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'query' => $this->query,
            'from' => $this->from,
            'offset' => $this->offset,
            'chat_type' => $this->chatType,
            'location' => $this->location,
        ], fn ($value) => $value !== null);
    }
}
