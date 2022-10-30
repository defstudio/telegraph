<?php

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\DTO\CallbackData;
use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\Exceptions\TelegramWebhookException;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class Callback
{
    /**
     * @var class-string<CallbackData>
     */
    public static string $callbackDataClass;

    protected TelegraphChat $chat;
    protected int $messageId;
    protected int $callbackQueryId;

    /**
     * @throws TelegramWebhookException
     */
    public function __construct(
        protected TelegraphBot $bot,
        protected CallbackQuery $callbackQuery,
        protected Request $request,
    ) {
        $this->extractCallbackQueryData();
    }

    public function data(): CallbackData
    {
        return $this->callbackQuery->data();
    }

    /**
     * @return class-string<CallbackData>
     */
    public static function getDataClass(): string
    {
        return static::$callbackDataClass;
    }

    abstract public function handle(): void;

    /**
     * @throws TelegramWebhookException
     * @throws NotFoundHttpException
     */
    protected function extractCallbackQueryData(): void
    {
        $this->messageId = $this->callbackQuery->message()?->id() ?? throw TelegramWebhookException::invalidData('message id missing');

        $this->callbackQueryId = $this->callbackQuery->id();

        /** @var TelegraphChat $chat */
        $chat = $this->bot->chats()->firstOrNew([
            'chat_id' => $this->request->input('callback_query.message.chat.id'),
        ]);

        $this->chat = $chat;

        if (!$this->chat->exists) {
            if (!config('telegraph.security.allow_callback_queries_from_unknown_chats', false)) {
                throw new NotFoundHttpException();
            }

            if (config('telegraph.security.store_unknown_chats_in_db', false)) {
                $this->chat->name = Str::of("")
                    ->append("[", $this->request->input('callback_query.message.chat.type'), ']')
                    ->append(" ", $this->request->input(
                        'callback_query.message.chat.username',
                        $this->request->input('callback_query.message.chat.title')
                    ));

                $this->chat->save();
            }
        }
    }
}
