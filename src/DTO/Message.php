<?php

/** @noinspection PhpUnused */

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Carbon\CarbonInterface;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Message implements Arrayable
{
    private int $id;

    private CarbonInterface $date;
    private ?CarbonInterface $editDate = null;

    private string $text;
    /** Can be string or json string. if json then convert it to array */
    private mixed $webAppData = null;
    private bool $protected = false;

    private ?User $from = null;
    private ?User $forwardedFrom = null;

    private ?Chat $chat = null;
    private Keyboard $keyboard;

    private ?Message $replyToMessage = null;

    /** @var Collection<array-key, User> */
    private Collection $newChatMembers;
    private ?User $leftChatMember = null;

    /** @var Collection<array-key, Photo> */
    private Collection $photos;
    private ?Animation $animation = null;
    private ?Audio $audio = null;
    private ?Document $document = null;
    private ?Video $video = null;
    private ?Location $location = null;
    private ?Contact $contact = null;
    private ?Voice $voice = null;

    private function __construct()
    {
        $this->photos = Collection::empty();
    }

    /**
     * @param array{
     *     message_id: int,
     *     date: int,
     *     edit_date?: int,
     *     text?: string,
     *     caption?: string,
     *     has_protected_content?: bool,
     *     from?: array<string, mixed>,
     *     forward_from?: array<string, mixed>,
     *     chat?: array<string, mixed>,
     *     reply_markup?: array<array<array<string>>>,
     *     reply_to_message?: array<string, mixed>,
     *     animation?:array<string, mixed>,
     *     audio?:array<string, mixed>,
     *     voice?:array<string, mixed>,
     *     document?: array<string, mixed>,
     *     video?: array<string, mixed>,
     *     photo?: array<string, mixed>,
     *     location?: array<string, mixed>,
     *     contact?: array<string, mixed>,
     *     new_chat_members?: array<string, mixed>,
     *     left_chat_member?: array<string, mixed>,
     *     web_app_data?: array<string, mixed>,
     *  } $data
     */
    public static function fromArray(array $data): Message
    {
        $message = new self();

        $message->id = $data['message_id'];

        $message->date = Carbon::createFromTimestamp($data['date']);

        if (isset($data['edit_date'])) {
            $message->editDate = Carbon::createFromTimestamp($data['edit_date']);
        }

        $message->text = $data['text'] ?? $data['caption'] ?? '';

        $message->protected = $data['has_protected_content'] ?? false;

        if (isset($data['reply_to_message'])) {
            /* @phpstan-ignore-next-line */
            $message->replyToMessage = Message::fromArray($data['reply_to_message']);
        }

        if (isset($data['from'])) {
            /* @phpstan-ignore-next-line */
            $message->from = User::fromArray($data['from']);
        }

        if (isset($data['forward_from'])) {
            /* @phpstan-ignore-next-line */
            $message->forwardedFrom = User::fromArray($data['forward_from']);
        }

        if (isset($data['chat'])) {
            /* @phpstan-ignore-next-line */
            $message->chat = Chat::fromArray($data['chat']);
        }

        if (isset($data['reply_markup']) && isset($data['reply_markup']['inline_keyboard'])) {
            /* @phpstan-ignore-next-line */
            $message->keyboard = Keyboard::fromArray($data['reply_markup']['inline_keyboard']);
        } else {
            $message->keyboard = Keyboard::make();
        }

        /* @phpstan-ignore-next-line */
        $message->photos = collect($data['photo'] ?? [])->map(fn (array $photoData) => Photo::fromArray($photoData));

        if (isset($data['animation'])) {
            /* @phpstan-ignore-next-line */
            $message->animation = Animation::fromArray($data['animation']);
        }

        if (isset($data['audio'])) {
            /* @phpstan-ignore-next-line */
            $message->audio = Audio::fromArray($data['audio']);
        }

        if (isset($data['document'])) {
            /* @phpstan-ignore-next-line */
            $message->document = Document::fromArray($data['document']);
        }

        if (isset($data['video'])) {
            /* @phpstan-ignore-next-line */
            $message->video = Video::fromArray($data['video']);
        }

        if (isset($data['location'])) {
            /* @phpstan-ignore-next-line */
            $message->location = Location::fromArray($data['location']);
        }


        if (isset($data['contact'])) {
            /* @phpstan-ignore-next-line */
            $message->contact = Contact::fromArray($data['contact']);
        }

        if (isset($data['voice'])) {
            /* @phpstan-ignore-next-line */
            $message->voice = Voice::fromArray($data['voice']);
        }

        /* @phpstan-ignore-next-line */
        $message->newChatMembers = collect($data['new_chat_members'] ?? [])->map(fn (array $userData) => User::fromArray($userData));


        if (isset($data['left_chat_member'])) {
            /* @phpstan-ignore-next-line */
            $message->leftChatMember = User::fromArray($data['left_chat_member']);
        }

        if (isset($data['web_app_data']['data'])) {
            $webAppData = json_decode($data['web_app_data']['data'], true);

            if (!$webAppData) {
                $webAppData = $data['web_app_data']['data'];
            }

            $message->webAppData = $webAppData;
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

    public function editDate(): ?CarbonInterface
    {
        return $this->editDate;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function protected(): bool
    {
        return $this->protected;
    }

    public function from(): ?User
    {
        return $this->from;
    }

    public function forwardFrom(): ?User
    {
        return $this->forwardedFrom;
    }

    public function chat(): ?Chat
    {
        return $this->chat;
    }

    public function keyboard(): Keyboard
    {
        return $this->keyboard;
    }

    public function replyToMessage(): ?Message
    {
        return $this->replyToMessage;
    }

    /**
     * @return Collection<array-key, Photo>
     */
    public function photos(): Collection
    {
        return $this->photos;
    }

    public function animation(): ?Animation
    {
        return $this->animation;
    }

    public function audio(): ?Audio
    {
        return $this->audio;
    }

    public function document(): ?Document
    {
        return $this->document;
    }

    public function video(): ?Video
    {
        return $this->video;
    }

    public function location(): ?Location
    {
        return $this->location;
    }

    public function contact(): ?Contact
    {
        return $this->contact;
    }

    public function voice(): ?Voice
    {
        return $this->voice;
    }

    /**
     * @return Collection<array-key, User>
     */
    public function newChatMembers(): Collection
    {
        return $this->newChatMembers;
    }

    public function leftChatMember(): ?User
    {
        return $this->leftChatMember;
    }

    public function webAppData(): mixed
    {
        return $this->webAppData;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'date' => $this->date->toISOString(),
            'edit_date' => $this->editDate?->toISOString(),
            'text' => $this->text,
            'protected' => $this->protected,
            'from' => $this->from?->toArray(),
            'forwarded_from' => $this->forwardedFrom?->toArray(),
            'chat' => $this->chat?->toArray(),
            'keyboard' => $this->keyboard->isFilled() ? $this->keyboard->toArray() : null,
            'reply_to_message' => $this->replyToMessage?->toArray(),
            'photos' => $this->photos->toArray(),
            'animation' => $this->animation?->toArray(),
            'audio' => $this->audio?->toArray(),
            'document' => $this->document?->toArray(),
            'video' => $this->video?->toArray(),
            'location' => $this->location?->toArray(),
            'contact' => $this->contact?->toArray(),
            'voice' => $this->voice?->toArray(),
            'new_chat_members' => $this->newChatMembers->toArray(),
            'left_chat_member' => $this->leftChatMember,
            'web_app_data' => $this->webAppData,
        ], fn ($value) => $value !== null);
    }
}
