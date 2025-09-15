<?php

/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Handlers;

use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\DTO\Chat;
use DefStudio\Telegraph\DTO\ChatJoinRequest;
use DefStudio\Telegraph\DTO\ChatMemberUpdate;
use DefStudio\Telegraph\DTO\InlineQuery;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\Poll;
use DefStudio\Telegraph\DTO\PollAnswer;
use DefStudio\Telegraph\DTO\PreCheckoutQuery;
use DefStudio\Telegraph\DTO\Reaction;
use DefStudio\Telegraph\DTO\ReactionType;
use DefStudio\Telegraph\DTO\SuccessfulPayment;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Exceptions\TelegramWebhookException;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use ReflectionMethod;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

abstract class WebhookHandler
{
    protected TelegraphBot $bot;
    protected TelegraphChat $chat;

    protected int $messageId;
    protected int $callbackQueryId;

    protected Request $request;
    protected Message|null $message = null;
    protected Reaction|null $reaction = null;
    protected CallbackQuery|null $callbackQuery = null;
    protected ChatJoinRequest|null $chatJoinRequest = null;

    /**
     * @var Collection<string, string>|Collection<int, array<string, string>>|Collection<array-key, ReactionType>
     */
    protected Collection $data;

    protected Keyboard $originalKeyboard;

    public function __construct()
    {
        $this->originalKeyboard = Keyboard::make();
    }

    public function handle(Request $request, TelegraphBot $bot): void
    {
        try {
            $this->bot = $bot;
            $this->request = $request;

            if ($this->request->has('inline_query')) {
                $this->handleInlineQuery(InlineQuery::fromArray($this->request->input('inline_query')));

                return;
            }

            if ($this->request->has('poll')) {
                $this->handlePollStateUpdate(Poll::fromArray($this->request->input('poll')));

                return;
            }

            if ($this->request->has('poll_answer')) {
                $this->handlePollAnswer(PollAnswer::fromArray($this->request->input('poll_answer')));

                return;
            }

            if ($this->request->has('pre_checkout_query')) {
                $this->handlePreCheckoutQuery(PreCheckoutQuery::fromArray($this->request->input('pre_checkout_query')));

                return;
            }

            if ($this->request->has('my_chat_member')) {
                $this->handleBotChatStatusUpdate(ChatMemberUpdate::fromArray($this->request->input('my_chat_member')));

                return;
            }

            // setup data
            $this->message = match (true) {
                $this->request->has('message') => Message::fromArray($this->request->input('message')),
                $this->request->has('edited_message') => Message::fromArray($this->request->input('edited_message')),
                $this->request->has('channel_post') => Message::fromArray($this->request->input('channel_post')),
                $this->request->has('edited_channel_post') => Message::fromArray($this->request->input('edited_channel_post')),
                default => null,
            };

            $this->reaction = match (true) {
                $this->request->has('message_reaction') => Reaction::fromArray($this->request->input('message_reaction')),
                default => null,
            };

            $this->callbackQuery = match (true) {
                $this->request->has('callback_query') => CallbackQuery::fromArray($this->request->input('callback_query')),
                default => null,
            };

            $this->chatJoinRequest = match (true) {
                $this->request->has('chat_join_request') => ChatJoinRequest::fromArray($this->request->input('chat_join_request')),
                default => null,
            };

            // setup chat
            $this->setupChat();

            // run handlers
            match (true) {
                isset($this->message) && $this->message->successfulPayment() => $this->handleSuccessfulPayment($this->message->successfulPayment()),
                isset($this->message) && $this->message->migrateToChatId() => $this->handleMigrateToChat(),
                isset($this->message) => $this->handleMessage(),
                isset($this->callbackQuery) => $this->handleCallbackQuery(),
                isset($this->chatJoinRequest) => $this->handleChatJoinRequest($this->chatJoinRequest),
                isset($this->reaction) => $this->handleReaction(),
                default => null,
            };
        } catch (Throwable $throwable) {
            $this->onFailure($throwable);
        }
    }

    protected function onFailure(Throwable $throwable): void
    {
        if ($throwable instanceof NotFoundHttpException) {
            throw $throwable;
        }

        report($throwable);

        rescue(fn () => $this->reply(__('telegraph::errors.webhook_error_occurred')), report: false);
    }

    //---- Chat Setup
    protected function setupChat(): void
    {
        $telegramChat = match (true) {
            isset($this->message) => $this->message->chat(),
            isset($this->reaction) => $this->reaction->chat(),
            isset($this->chatJoinRequest) => $this->chatJoinRequest->chat(),
            default => $this->callbackQuery?->message()?->chat(),
        };

        assert($telegramChat !== null);

        $this->chat = $this->bot->chats()->firstOrNew([
            'chat_id' => $telegramChat->id(),
        ]);

        if (!$this->chat->exists) {
            if (!$this->allowUnknownChat()) {
                throw new NotFoundHttpException();
            }

            if (config('telegraph.security.store_unknown_chats_in_db', false)) {
                $this->createChat($telegramChat, $this->chat);
            }
        }
    }

    protected function allowUnknownChat(): bool
    {
        return (bool)match (true) {
            isset($this->message),
            isset($this->reaction) => config('telegraph.security.allow_messages_from_unknown_chats', false),
            isset($this->callbackQuery) => config('telegraph.security.allow_callback_queries_from_unknown_chats', false),
            default => false,
        };
    }

    protected function createChat(Chat $telegramChat, TelegraphChat $chat): void
    {
        $chat->name = $this->getChatName($telegramChat);
        $chat->save();
    }

    protected function getChatName(Chat $chat): string
    {
        return Str::of("")
            ->append("[", $chat->type(), ']')
            ->append(" ", $chat->title());
    }

    //---- Message Handlers
    protected function handleMessage(): void
    {
        $this->extractMessageData();

        if (config('telegraph.debug_mode', config('telegraph.webhook.debug'))) {
            Log::debug('Telegraph webhook message', $this->data->toArray());
        }

        $text = Str::of($this->message?->text() ?? '');

        if ($this->isCommand($text)) {
            $this->handleCommand($text);

            return;
        }

        if ($this->message?->newChatMembers()->isNotEmpty()) {
            foreach ($this->message->newChatMembers() as $member) {
                $this->handleChatMemberJoined($member);
            }

            return;
        }

        if ($this->message?->leftChatMember() !== null) {
            $this->handleChatMemberLeft($this->message->leftChatMember());

            return;
        }

        $this->handleChatMessage($text);
    }

    protected function extractMessageData(): void
    {
        assert($this->message !== null);

        $this->messageId = $this->message->id();

        $this->data = collect([
            'text' => $this->message->text(),
        ]);
    }

    protected function isCommand(Stringable $text): bool
    {
        $commandPrefixes = $this->commandPrefixes();

        $firstLetters = $commandPrefixes->map->substr(0, 1);

        $foundPrefix = $commandPrefixes->first(function (Stringable $prefix) use ($commandPrefixes, $firstLetters, $text) {
            if (!$text->startsWith($prefix)) {
                return false;
            }

            $cut = $text->substr(
                Str::length($prefix)
            )->before(' ');

            if ($cut->startsWith($commandPrefixes) || $cut->startsWith($firstLetters)) {
                return false;
            }

            return true;
        });

        return $foundPrefix !== null;
    }

    /**
     * @return Collection<int, Stringable>
     */
    protected function commandPrefixes(): Collection
    {
        /** @var string[] $prefixes */
        $prefixes = config('telegraph.commands.start_with', []);

        return collect($prefixes)
            ->push('/')
            ->map(fn (string $prefix) => str($prefix)->trim())
            ->unique()
            ->values();
    }

    protected function handleCommand(Stringable $text): void
    {
        [$command, $parameter] = $this->parseCommand($text);

        if (!$this->canHandle($command)) {
            $this->handleUnknownCommand($text);

            return;
        }

        $this->$command($parameter);
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected function parseCommand(Stringable $text): array
    {
        $command = $text->before('@')->before(' ');

        foreach ($this->commandPrefixes() as $prefix) {
            if ($command->startsWith($prefix)) {
                $parameter = $text->after($command)->after('@')->after(' ');
                $command = $command->after($prefix);

                break;
            }
        }

        return [(string)$command, (string)($parameter ?? '')];
    }

    protected function canHandle(string $action): bool
    {
        if ($action === 'handle') {
            return false;
        }

        if (!method_exists($this, $action)) {
            return false;
        }

        $reflector = new ReflectionMethod($this::class, $action);
        if (!$reflector->isPublic()) {
            return false;
        }

        return true;
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        if ($this->message?->chat()?->type() === Chat::TYPE_PRIVATE) {
            if (config('telegraph.report_unknown_webhook_commands', config('telegraph.webhook.report_unknown_commands', true))) {
                report(TelegramWebhookException::invalidCommand($this->parseCommand($text)[0]));
            }

            $this->chat->html(__('telegraph::errors.invalid_command'))->send();
        }
    }

    //---- CallbackQuery Handlers
    protected function handleCallbackQuery(): void
    {
        $this->extractCallbackQueryData();

        if (config('telegraph.debug_mode', config('telegraph.webhook.debug'))) {
            Log::debug('Telegraph webhook callback', $this->data->toArray());
        }

        /** @var string $action */
        $action = $this->callbackQuery?->data()->get('action') ?? '';

        if (!$this->canHandle($action)) {
            report(TelegramWebhookException::invalidAction($action));
            $this->reply(__('telegraph::errors.invalid_action'));

            return;
        }

        /** @phpstan-ignore-next-line */
        App::call([$this, $action], $this->data->toArray());
    }

    protected function extractCallbackQueryData(): void
    {
        assert($this->callbackQuery !== null);

        $this->messageId = $this->callbackQuery->message()?->id() ?? throw TelegramWebhookException::invalidData('message id missing');

        $this->callbackQueryId = $this->callbackQuery->id();

        /** @phpstan-ignore-next-line */
        $this->originalKeyboard = $this->callbackQuery->message()?->keyboard() ?? Keyboard::make();

        $this->data = $this->callbackQuery->data();
    }

    //---- Reaction Handlers
    protected function handleReaction(): void
    {
        $this->extractReactionData();

        if (config('telegraph.debug_mode', config('telegraph.webhook.debug'))) {
            Log::debug('Telegraph webhook message', $this->data->toArray());
        }

        /** @phpstan-ignore-next-line */
        $this->handleChatReaction($this->reaction->newReaction(), $this->reaction->oldReaction());
    }

    protected function extractReactionData(): void
    {
        assert($this->reaction !== null);

        $this->messageId = $this->reaction->id();

        $this->data = $this->reaction->newReaction();
    }

    //---- Handlers
    protected function handlePreCheckoutQuery(PreCheckoutQuery $preCheckoutQuery): void
    {
        $this->bot->answerPreCheckoutQuery($preCheckoutQuery->id(), true)->send();
    }

    protected function handleSuccessfulPayment(SuccessfulPayment $successfulPayment): void
    {
        // .. do nothing
    }

    protected function handleInlineQuery(InlineQuery $inlineQuery): void
    {
        // .. do nothing
    }

    protected function handlePollStateUpdate(Poll $poll): void
    {
        // .. do nothing
    }

    protected function handlePollAnswer(PollAnswer $pollAnswer): void
    {
        // .. do nothing
    }

    protected function handleChatMessage(Stringable $text): void
    {
        // .. do nothing
    }

    protected function handleChatJoinRequest(ChatJoinRequest $chatJoinRequest): void
    {
        // .. do nothing
    }

    protected function handleChatMemberJoined(User $member): void
    {
        // .. do nothing
    }

    protected function handleChatMemberLeft(User $member): void
    {
        // .. do nothing
    }

    protected function handleBotChatStatusUpdate(ChatMemberUpdate $chatMemberUpdate): void
    {
        // .. do nothing
    }

    /**
     * @param Collection<array-key, Reaction> $newReactions
     * @param Collection<array-key, Reaction> $oldReactions
     *
     * @return void
     */
    protected function handleChatReaction(Collection $newReactions, Collection $oldReactions): void
    {
        // .. do nothing
    }

    protected function handleMigrateToChat(): void
    {
        /** @phpstan-ignore-next-line */
        $this->chat->chat_id = $this->message->migrateToChatId();
        $this->chat->save();
    }

    //---- Helpers
    protected function replaceKeyboard(Keyboard $newKeyboard): void
    {
        $this->chat->replaceKeyboard($this->messageId, $newKeyboard)->send();
    }

    protected function deleteKeyboard(): void
    {
        $this->chat->deleteKeyboard($this->messageId)->send();
    }

    protected function reply(string $message, bool $showAlert = false): void
    {
        if (isset($this->callbackQueryId)) {
            $this->bot->replyWebhook($this->callbackQueryId, $message, $showAlert)->send();

            return;
        }

        $this->chat->message($message)->send();
    }

    public function chatid(): void
    {
        $this->chat->html("Chat ID: {$this->chat->chat_id}")->send();
    }
}
