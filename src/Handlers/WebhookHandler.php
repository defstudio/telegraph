<?php

/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Handlers;

use DefStudio\Telegraph\Exceptions\TelegramWebhookException;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionMethod;

abstract class WebhookHandler
{
    protected string $chatId;
    protected string $messageId;
    protected string $callbackQueryId;
    protected Request $request;
    protected Collection $data;
    protected Collection $originalKeyboard;

    protected function extractData(): void
    {
        $this->chatId = $this->request->input('callback_query.message.chat.id'); //@phpstan-ignore-line
        $this->messageId = $this->request->input('callback_query.message.message_id'); //@phpstan-ignore-line
        $this->callbackQueryId = $this->request->input('callback_query.id'); //@phpstan-ignore-line
        $this->originalKeyboard = collect($this->request->input('callback_query.message.reply_markup.inline_keyboard', []))->flatten(1); //@phpstan-ignore-line
        $this->data = Str::of($this->request->input('callback_query.data'))->explode(';') //@phpstan-ignore-line
        ->mapWithKeys(function (string $entity) {
            $entity = explode(':', $entity);
            $key = $entity[0];
            $value = $entity[1];

            return [$key => $value];
        });
    }

    protected function reply(string $message): void
    {
        Telegraph::replyWebhook($this->callbackQueryId, $message)->send();
    }

    /**
     * @param array<array<array<non-empty-string, non-empty-string>>> $newKeyboard
     */
    protected function replaceKeyboard(array $newKeyboard): void
    {
        Telegraph::chat($this->chatId)->replaceKeyboard($this->messageId, $newKeyboard)->send();
    }

    protected function deleteKeyboard(): void
    {
        Telegraph::chat($this->chatId)->replaceKeyboard($this->messageId, [])->send();
    }

    protected function canHandle(string $action): bool
    {
        if (!method_exists($this, $action)) {
            return false;
        }

        $reflector = new ReflectionMethod($this::class, $action);
        if (!$reflector->isPublic()) {
            return false;
        }

        if (method_exists(WebhookHandler::class, $action)) {
            throw TelegramWebhookException::invalidActionName($action);
        }

        return true;
    }

    public function handle(Request $request): void
    {
        $this->request = $request;
        $this->extractData();

        $action = $this->data->get('action');

        if (!$this->canHandle($action)) {
            report(TelegramWebhookException::invalidAction($action));
            $this->reply('Invalid action');

            return;
        }

        $this->$action();
    }
}
