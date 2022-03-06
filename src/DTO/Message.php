<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Carbon\CarbonInterface;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Carbon;

class Message
{
    private int $id;
    private CarbonInterface $date;
    private string $text;

    private ?User $from;
    private ?Chat $chat;
    private Keyboard $keyboard;

    private function __construct()
    {
    }

    /**
     * @param array{message_id:int, date:int, text:string, from:array<string, mixed>, chat:array<string, mixed>, reply_markup:array<string, mixed>} $data
     */
    public static function fromArray(array $data): Message
    {
        $message = new self();

        $message->id = $data['message_id'];

        $message->date = Carbon::createFromTimestamp($data['date']);

        $message->text = $data['text'] ?? '';

        if (isset($data['from'])) {
            $message->from = User::fromArray($data['from']);
        }

        if (isset($data['chat'])) {
            $message->chat = Chat::fromArray($data['chat']);
        }

        if (isset($data['reply_markup']) && isset($data['reply_markup']['inline_keyboard'])) {
            $message->keyboard = Keyboard::fromArray($data['reply_markup']['inline_keyboard']);
        } else {
            $message->keyboard = Keyboard::make();
        }

        return $message;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function date(): CarbonInterface
    {
        return $this->date;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function from(): ?User
    {
        return $this->from;
    }

    public function chat(): ?Chat
    {
        return $this->chat;
    }

    public function keyboard(): Keyboard
    {
        return $this->keyboard;
    }
}
