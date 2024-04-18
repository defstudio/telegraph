<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class ForumTopic implements Arrayable
{
    private string $name;
    private int $iconColor;

    /**
     * @param array{name: string, icon_color: int} $data
     */
    public static function fromArray(array $data, $id): ForumTopic
    {
        $topic = new self();

        $topic->name = $data['name'];
        $topic->iconColor = $data['icon_color'];

        return $topic;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function iconColor(): int
    {
        return $this->iconColor;
    }

    public function toArray(): array
    {
        return array_filter([
            'name'       => $this->name,
            'icon_color' => $this->iconColor,
        ], fn($value) => $value !== null);
    }
}
