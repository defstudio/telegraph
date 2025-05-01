<?php

/** @noinspection PhpUnused */

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Carbon\CarbonInterface;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int|bool|array<string, mixed>>
 */
class Message implements Arrayable
{
    private int $id;
    private ?int $messageThreadId = null;
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
    private ?Sticker $sticker = null;
    private ?Venue $venue = null;
    private ?Invoice $invoice = null;
    private ?SuccessfulPayment $successfulPayment = null;
    private ?RefundedPayment $refundedPayment = null;
    private ?WriteAccessAllowed $writeAccessAllowed = null;
    private ?UsersShared $usersShared = null;
    private ?ChatShared $chatShared = null;

    private ?string $migrateToChatId = null;

    /** @var Collection<array-key, Entity> */
    private Collection $entities;

    private function __construct()
    {
        $this->photos = Collection::empty();
        $this->entities = Collection::empty();
    }

    /**
     * @param array{
     *     message_id: int,
     *     message_thread_id?: int,
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
     *     sticker?:array<string, mixed>,
     *     document?: array<string, mixed>,
     *     video?: array<string, mixed>,
     *     photo?: array<string, mixed>,
     *     location?: array<string, mixed>,
     *     venue?: array<string, mixed>,
     *     contact?: array<string, mixed>,
     *     invoice?: array<string, mixed>,
     *     successful_payment?: array<string, mixed>,
     *     refunded_payment?: array<string, mixed>,
     *     new_chat_members?: array<string, mixed>,
     *     left_chat_member?: array<string, mixed>,
     *     web_app_data?: array<string, mixed>,
     *     write_access_allowed?: array<string, mixed>,
     *     users_shared?: array<string, mixed>,
     *     chat_shared?: array<string, mixed>,
     *     migrate_to_chat_id?: int,
     *     entities?: array<object>
     *  } $data
     */
    public static function fromArray(array $data): Message
    {
        $message = new self();

        $message->id = $data['message_id'];

        if (isset($data['message_thread_id'])) {
            $message->messageThreadId = $data['message_thread_id'];
        }

        $message->date = Carbon::createFromTimestamp($data['date']);

        if (isset($data['edit_date'])) {
            $message->editDate = Carbon::createFromTimestamp($data['edit_date']);
        }

        $message->text = $data['text'] ?? $data['caption'] ?? '';

        $message->protected = $data['has_protected_content'] ?? false;

        if (isset($data['reply_to_message'])) {
            $message->replyToMessage = Message::fromArray($data['reply_to_message']);
        }

        if (isset($data['from'])) {
            $message->from = User::fromArray($data['from']);
        }

        if (isset($data['forward_from'])) {
            $message->forwardedFrom = User::fromArray($data['forward_from']);
        }

        if (isset($data['chat'])) {
            $message->chat = Chat::fromArray($data['chat']);
        }

        if (isset($data['reply_markup']) && isset($data['reply_markup']['inline_keyboard'])) {
            $message->keyboard = Keyboard::fromArray($data['reply_markup']['inline_keyboard']);
        } else {
            $message->keyboard = Keyboard::make();
        }

        /* @phpstan-ignore-next-line */
        $message->photos = collect($data['photo'] ?? [])->map(fn (array $photoData) => Photo::fromArray($photoData));

        if (isset($data['animation'])) {
            $message->animation = Animation::fromArray($data['animation']);
        }

        if (isset($data['audio'])) {
            $message->audio = Audio::fromArray($data['audio']);
        }

        if (isset($data['document'])) {
            $message->document = Document::fromArray($data['document']);
        }

        if (isset($data['video'])) {
            $message->video = Video::fromArray($data['video']);
        }

        if (isset($data['location'])) {
            $message->location = Location::fromArray($data['location']);
        }


        if (isset($data['contact'])) {
            $message->contact = Contact::fromArray($data['contact']);
        }

        if (isset($data['voice'])) {
            $message->voice = Voice::fromArray($data['voice']);
        }

        if (isset($data['sticker'])) {
            $message->sticker = Sticker::fromArray($data['sticker']);
        }

        if (isset($data['venue'])) {
            $message->venue = Venue::fromArray($data['venue']);
        }

        if (isset($data['invoice'])) {
            $message->invoice = Invoice::fromArray($data['invoice']);
        }

        if (isset($data['successful_payment'])) {
            $message->successfulPayment = SuccessfulPayment::fromArray($data['successful_payment']);
        }

        if (isset($data['refunded_payment'])) {
            /* @phpstan-ignore-next-line */
            $message->refundedPayment = RefundedPayment::fromArray($data['refunded_payment']);
        }

        /* @phpstan-ignore-next-line */
        $message->newChatMembers = collect($data['new_chat_members'] ?? [])->map(fn (array $userData) => User::fromArray($userData));


        if (isset($data['left_chat_member'])) {
            $message->leftChatMember = User::fromArray($data['left_chat_member']);
        }

        if (isset($data['web_app_data']['data'])) {
            $webAppData = json_decode($data['web_app_data']['data'], true);

            if (!$webAppData) {
                $webAppData = $data['web_app_data']['data'];
            }

            $message->webAppData = $webAppData;
        }

        if (isset($data['write_access_allowed'])) {
            $message->writeAccessAllowed = WriteAccessAllowed::fromArray($data['write_access_allowed']);
        }

        if (isset($data['user_shared'])) {
            $message->usersShared = UsersShared::fromArray($data['user_shared']);
        }

        if (isset($data['chat_shared'])) {
            $message->chatShared = ChatShared::fromArray($data['chat_shared']);
        }

        if (isset($data['entities']) && $data['entities']) {
            /* @phpstan-ignore-next-line */
            $message->entities = collect($data['entities'])->map(fn (array $entity) => Entity::fromArray($entity));
        }

        if (isset($data['migrate_to_chat_id'])) {
            $message->migrateToChatId = (string) $data['migrate_to_chat_id'];
        }


        return $message;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function messageThreadId(): ?int
    {
        return $this->messageThreadId;
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

    public function sticker(): ?Sticker
    {
        return $this->sticker;
    }

    public function venue(): ?Venue
    {
        return $this->venue;
    }

    public function invoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function successfulPayment(): ?SuccessfulPayment
    {
        return $this->successfulPayment;
    }

    public function refundedPayment(): ?RefundedPayment
    {
        return $this->refundedPayment;
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

    public function writeAccessAllowed(): ?WriteAccessAllowed
    {
        return $this->writeAccessAllowed;
    }

    public function usersShared(): ?UsersShared
    {
        return $this->usersShared;
    }

    public function chatShared(): ?ChatShared
    {
        return $this->chatShared;
    }

    public function migrateToChatId(): ?string
    {
        return $this->migrateToChatId;
    }

    /**
     * @return Collection<array-key, Entity>
     */
    public function entities(): Collection
    {
        return $this->entities;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'message_thread_id' => $this->messageThreadId,
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
            'sticker' => $this->sticker?->toArray(),
            'venue' => $this->venue?->toArray(),
            'invoice' => $this->invoice?->toArray(),
            'successful_payment' => $this->successfulPayment?->toArray(),
            'refunded_payment' => $this->refundedPayment?->toArray(),
            'new_chat_members' => $this->newChatMembers->toArray(),
            'left_chat_member' => $this->leftChatMember,
            'web_app_data' => $this->webAppData,
            'write_access_allowed' => $this->writeAccessAllowed?->toArray(),
            'users_shared' => $this->usersShared?->toArray(),
            'chat_shared' => $this->chatShared?->toArray(),
            'migrate_to_chat_id' => (int) $this->migrateToChatId,
            'entities' => $this->entities->toArray(),
        ], fn ($value) => $value !== null);
    }
}
