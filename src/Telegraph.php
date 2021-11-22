<?php

/** @noinspection PhpUnused */
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\Contracts\TelegraphContract;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class Telegraph implements TelegraphContract
{
    public const PARSE_HTML = 'html';
    public const PARSE_MARKDOWN = 'markdown';

    private const TELEGRAM_API_BASE_URL = 'https://api.telegram.org/bot';
    public const ENDPOINT_SET_WEBHOOK = 'setWebhook';
    public const ENDPOINT_GET_WEBHOOK_DEBUG_INFO = 'getWebhookInfo';
    public const ENDPOINT_ANSWER_WEBHOOK = 'answerCallbackQuery';
    public const ENDPOINT_REPLACE_KEYBOARD = 'editMessageReplyMarkup';
    public const ENDPOINT_MESSAGE = 'sendMessage';

    protected string $endpoint;

    /** @var array<string, mixed> */
    protected array $data = [];

    protected TelegraphBot|null $bot;

    protected TelegraphChat|null $chat;

    protected string $message;

    /** @var array<array<array<string, string>>> */
    protected array $keyboard;

    protected string $parseMode;

    public function __construct()
    {
        $this->bot = rescue(fn () => TelegraphBot::query()->with('chats')->sole(), report: false); //@phpstan-ignore-line
        $this->chat = rescue(fn () => $this->bot?->chats()->sole(), report: false); //@phpstan-ignore-line

        $this->parseMode = config('telegraph.default_parse_mode', 'html'); //@phpstan-ignore-line
    }

    protected function sendRequestToTelegram(): Response
    {
        return Http::get($this->getUrl());
    }

    protected function buildChatMessage(): void
    {
        if (empty($this->chat)) {
            throw TelegraphException::missingChat();
        }

        $this->endpoint = self::ENDPOINT_MESSAGE;
        $this->data = [
            'text' => $this->message,
            'chat_id' => $this->chat->chat_id,
            'parse_mode' => $this->parseMode,
        ];

        if (!empty($this->keyboard)) {
            $this->data['reply_markup'] = json_encode([
                'inline_keyboard' => $this->keyboard,
            ]);
        }
    }

    public function getUrl(): string
    {
        if (empty($this->bot)) {
            throw TelegraphException::missingBot();
        }

        if (empty($this->endpoint)) {
            $this->buildChatMessage();
        }

        /** @phpstan-ignore-next-line */
        return (string) Str::of(self::TELEGRAM_API_BASE_URL)
            ->append($this->bot?->token)
            ->append('/', $this->endpoint)
            ->when(!empty($this->data), fn (Stringable $str) => $str->append('?', http_build_query($this->data)));
    }

    public function bot(TelegraphBot $bot): Telegraph
    {
        $this->bot = $bot;

        if (empty($this->chat)) {
            $this->chat = rescue(fn () => $this->bot->chats()->sole(), report: false); //@phpstan-ignore-line
        }

        return $this;
    }

    public function chat(TelegraphChat $chat): Telegraph
    {
        $this->chat = $chat;
        $this->bot = $this->chat->bot;

        return $this;
    }

    public function message(string $message): Telegraph
    {
        return match (config('telegraph.default_parse_mode')) {
            self::PARSE_MARKDOWN => $this->markdown($message),
            default => $this->html($message)
        };
    }

    public function html(string $message): Telegraph
    {
        $this->message = $message;
        $this->parseMode = 'html';

        return $this;
    }

    public function markdown(string $message): Telegraph
    {
        $this->message = $message;
        $this->parseMode = 'markdown';

        return $this;
    }

    /**
     * @param array<array<array<string, string>>> $keyboard
     */
    public function keyboard(array $keyboard): Telegraph
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    public function registerWebhook(): Telegraph
    {
        if (empty($this->bot)) {
            throw TelegraphException::missingBot();
        }

        $this->endpoint = self::ENDPOINT_SET_WEBHOOK;
        $this->data = [
            'url' => route('telegraph.webhook', $this->bot),
        ];

        return $this;
    }

    public function getWebhookDebugInfo(): Telegraph
    {
        $this->endpoint = self::ENDPOINT_GET_WEBHOOK_DEBUG_INFO;

        return $this;
    }

    public function replyWebhook(string $callbackQueryId, string $message): Telegraph
    {
        $this->endpoint = self::ENDPOINT_ANSWER_WEBHOOK;
        $this->data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $message,
        ];

        return $this;
    }

    /**
     * @param array<array<array<string, string>>> $newKeyboard
     */
    public function replaceKeyboard(string $messageId, array $newKeyboard): Telegraph
    {
        if (empty($this->chat)) {
            throw TelegraphException::missingChat();
        }

        if (empty($newKeyboard)) {
            $replyMarkup = null;
        } else {
            $replyMarkup = json_encode(['inline_keyboard' => $newKeyboard]);
        }

        $this->endpoint = self::ENDPOINT_REPLACE_KEYBOARD;
        $this->data = [
            'chat_id' => $this->chat->chat_id,
            'message_id' => $messageId,
            'reply_markup' => $replyMarkup,
        ];

        return $this;
    }

    public function deleteKeyboard(string $messageId): Telegraph
    {
        return $this->replaceKeyboard($messageId, []);
    }

    public function send(): Response
    {
        return $this->sendRequestToTelegram();
    }
}
