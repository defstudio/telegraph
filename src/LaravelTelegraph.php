<?php /** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\LaravelTelegraph;

use DefStudio\LaravelTelegraph\Exceptions\TelegramException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class LaravelTelegraph
{
    private const TELEGRAM_API_BASE_URL = 'https://api.telegram.org/bot';
    private const ENDPOINT_SET_WEBHOOK = 'setWebhook';
    private const ENDPOINT_ANSWER_WEBHOOK = 'answerCallbackQuery';
    private const ENDPOINT_REPLACE_KEYBOARD = 'editMessageReplyMarkup';
    private const ENDPOINT_MESSAGE = 'sendMessage';

    private string $endpoint;
    private array $data = [];
    private string $botToken;
    private string $chatId;

    private string $message;
    private array $keyboard;
    private string $parseMode = 'html';

    public function __construct()
    {
        $this->botToken = config('telegraph.bot_token');
        $this->chatId = config('telegraph.chat_id');
        $this->parseMode = config('telegraph.default_parse_mode');
    }

    private function sendRequestToTelegram(): Response
    {
        return \Http::get($this->buildUrl());
    }

    private function checkRequirements(): void
    {
        if (empty($this->botToken)) {
            throw TelegramException::missingBotToken();
        }

        if (empty($this->chatId)) {
            throw TelegramException::missingChatId();
        }
    }

    private function buildUrl()
    {
        return Str::of(self::TELEGRAM_API_BASE_URL)
            ->append($this->botToken)
            ->append('/', $this->endpoint)
            ->when(!empty($this->data), fn (Stringable $str) => $str->append('?', http_build_query($this->data)));
    }

    public function bot(string $botToken): LaravelTelegraph
    {
        $this->botToken = $botToken;
        return $this;
    }

    public function chat(string $chatId): LaravelTelegraph
    {
        $this->chatId = $chatId;
        return $this;
    }

    public function html(string $message): LaravelTelegraph
    {
        $this->message = $message;
        $this->parseMode = 'html';
        return $this;
    }

    public function markdown(string $message): LaravelTelegraph
    {
        $this->message = $message;
        $this->parseMode = 'markdown';
        return $this;
    }


    /**
     * @param array<array<array<non-empty-string, non-empty-string>>> $keyboard
     */
    public function keyboard(array $keyboard): LaravelTelegraph
    {
        $this->keyboard = $keyboard;
        return $this;
    }

    public function registerWebhook(): LaravelTelegraph
    {
        $this->endpoint = self::ENDPOINT_SET_WEBHOOK;
        $this->data = [
            'url' => route('telegraph.webhook', config('telegraph.bot_token')),
        ];

        return $this;
    }

    public function answerWebhook(string $callbackQueryId, string $message): LaravelTelegraph
    {
        $this->endpoint = self::ENDPOINT_ANSWER_WEBHOOK;
        $this->data = [
            'callback_query_id' => $callbackQueryId,
            'text'              => $message,
        ];

        return $this;
    }

     /**
      * @param array<array<array<non-empty-string, non-empty-string>>> $newKeyboard
      */
    public function replaceKeyboard(string $messageId, array $newKeyboard): LaravelTelegraph
    {
        $this->checkRequirements();

        if(empty($newKeyboard)){
            $replyMarkup = null;
        }else{
            $replyMarkup = json_encode(['inline_keyboard' => $newKeyboard]);
        }

        $this->endpoint = self::ENDPOINT_REPLACE_KEYBOARD;
        $this->data = [
            'chat_id' => $this->chatId,
            'message_id' => $messageId,
            'reply_markup' => $replyMarkup,
        ];

        return $this;
    }

    private function buildChatMessage(): void
    {
        $this->endpoint = self::ENDPOINT_MESSAGE;
        $this->data = [
            'text' => $this->message,
            'chat_id' => $this->chatId,
            'parse_mode' => $this->parseMode
        ];

        if(!empty($this->keyboard)){
            $this->data['reply_markup'] = json_encode([
                'inline_keyboard' => $this->keyboard
            ]);
        }
    }

    public function send(): Response
    {
        $this->checkRequirements();

        if(empty($this->endpoint)){
            $this->buildChatMessage();
        }

        return $this->sendRequestToTelegram();
    }
}
