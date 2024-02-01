<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CallbackQuery implements Arrayable
{
    private int $id;

    private User $from;

    private Message|null $message = null;

    private Collection $data;

    private function __construct()
    {
    }

    /**
     * @param array{id:int, from:array<string, mixed>, message?:array<string, mixed>, data?:string} $data
     */
    public static function fromArray(array $data): CallbackQuery
    {
        $callbackQuery = new self();

        $callbackQuery->id = $data['id'];

        /* @phpstan-ignore-next-line */
        $callbackQuery->from = User::fromArray($data['from']);

        if (isset($data['message'])) {
            /* @phpstan-ignore-next-line */
            $callbackQuery->message = Message::fromArray($data['message']);
        }

        $callbackQuery->data = Str::of($data['data'] ?? '')
            ->explode(';')
            ->filter()
            /* @phpstan-ignore-next-line */
            ->mapWithKeys(function (string $entity) {
                $entity = explode(':', $entity);
                $key = $entity[0];
                $value = $entity[1];

                return [$key => $value];
            });

        return $callbackQuery;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function from(): User
    {
        return $this->from;
    }

    public function message(): Message|null
    {
        return $this->message;
    }

    public function data(): Collection
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'from' => $this->from->toArray(),
            'message' => $this->message?->toArray(),
            'data' => $this->data->toArray(),
        ], fn ($value) => $value !== null);
    }
}
