<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class InlineQuery implements Arrayable
{
    private int $id;
    private string $query;
    private User $from;
    private string $offset;
    private string $chatType;
    private ?Location $location;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     id: string,
     *     from: array<string, mixed>,
     *     query: string,
     *     offset: string,
     *     chat_type: string,
     *     location?: array<string, mixed>
     * } $data
     */
    public static function fromArray(array $data): InlineQuery
    {
        $inlineQuery = new self();

        $inlineQuery->id = $data['id'];
        $inlineQuery->from = User::fromArray($data['from']);
        $inlineQuery->query = $data['query'];
        $inlineQuery->offset = $data['offset'];
        $inlineQuery->chatType = $data['chat_type'];

        if (isset($data['location'])) {
            $inlineQuery->location = $data['location'];
        }

        return $inlineQuery;
    }

    public function id(): int
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
            'chatType' => $this->chatType,
            'location' => $this->location,
        ]);
    }
}
