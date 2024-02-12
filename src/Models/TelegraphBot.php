<?php

/** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Models;

use DefStudio\Telegraph\Concerns\HasStorage;
use DefStudio\Telegraph\Contracts\Downloadable;
use DefStudio\Telegraph\Contracts\Storable;
use DefStudio\Telegraph\Database\Factories\TelegraphBotFactory;
use DefStudio\Telegraph\DTO\InlineQueryResult;
use DefStudio\Telegraph\DTO\TelegramUpdate;
use DefStudio\Telegraph\Exceptions\TelegramUpdatesException;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * DefStudio\Telegraph\Models\TelegraphBot
 *
 * @property int $id
 * @property string $token
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<TelegraphChat> $chats
 */
class TelegraphBot extends Model implements Storable
{
    use HasFactory;
    use HasStorage;

    protected $fillable = [
        'token',
        'name',
    ];

    protected static function newFactory(): Factory
    {
        return TelegraphBotFactory::new();
    }

    public static function booted()
    {
        self::created(function (TelegraphBot $bot) {
            if (empty($bot->name)) {
                $bot->name = "Bot #$bot->id";
                $bot->saveQuietly();
            }
        });
    }

    public function storageKey(): string|int
    {
        return $this->id;
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public static function fromId(int $id = null): TelegraphBot
    {
        if (empty($id)) {
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            /** @phpstan-ignore-next-line */
            return self::query()->sole();
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        /** @phpstan-ignore-next-line */
        return self::query()->findOrFail($id);
    }

    public static function fromToken(string $token): TelegraphBot
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        /** @phpstan-ignore-next-line */
        return self::query()->where('token', $token)->sole();
    }

    public function chats(): HasMany
    {
        /** @phpstan-ignore-next-line */
        return $this->hasMany(config('telegraph.models.chat'), 'telegraph_bot_id');
    }

    public function registerWebhook(): Telegraph
    {
        return TelegraphFacade::bot($this)->registerWebhook();
    }

    public function unregisterWebhook(bool $dropPendingUpdates = false): Telegraph
    {
        return TelegraphFacade::bot($this)->unregisterWebhook($dropPendingUpdates);
    }

    public function getWebhookDebugInfo(): Telegraph
    {
        return TelegraphFacade::bot($this)->getWebhookDebugInfo();
    }

    public function replyWebhook(int $callbackQueryId, string $message, bool $showAlert = false): Telegraph
    {
        return TelegraphFacade::bot($this)->replyWebhook($callbackQueryId, $message, $showAlert);
    }

    /**
     * @param InlineQueryResult[] $results
     */
    public function answerInlineQuery(string $inlineQueryID, array $results): Telegraph
    {
        return TelegraphFacade::bot($this)->answerInlineQuery($inlineQueryID, $results);
    }

    /**
     * @param array<string, string> $commands
     */
    public function registerCommands(array $commands): Telegraph
    {
        return TelegraphFacade::bot($this)->registerBotCommands($commands);
    }

    public function getRegisteredCommands(): Telegraph
    {
        return TelegraphFacade::bot($this)->getRegisteredCommands();
    }

    public function unregisterCommands(): Telegraph
    {
        return TelegraphFacade::bot($this)->unregisterBotCommands();
    }

    /**
     * @return array{id: int, is_bot: bool, first_name: string, username: string, can_join_groups: bool, can_read_all_group_messages: bool, support_inline_queries: bool}
     */
    public function info(): array
    {
        $reply = TelegraphFacade::bot($this)->botInfo()->send();

        if ($reply->telegraphError()) {
            throw TelegraphException::failedToRetrieveBotInfo();
        }

        /* @phpstan-ignore-next-line */
        return $reply->json('result');
    }

    public function getFileInfo(string $fileId): Telegraph
    {
        return TelegraphFacade::bot($this)->getFileInfo($fileId);
    }

    public function store(Downloadable|string $attachment, string $path, string $filename = null): string
    {
        return TelegraphFacade::bot($this)->store($attachment, $path, $filename);
    }

    public function url(): string
    {
        return "https://t.me/".$this->info()['username'];
    }

    /**
     * @param string[]|null $allowedUpdates
     *
     * @return \Illuminate\Support\Collection<int, TelegramUpdate>
     */
    public function updates(int $timeout = null, int $offset = null, int $limit = null, array $allowedUpdates = null): \Illuminate\Support\Collection
    {
        $reply = TelegraphFacade::bot($this)->botUpdates($timeout, $offset, $limit, $allowedUpdates)->send();

        if ($reply->telegraphError()) {
            if (!$reply->successful()) {
                throw TelegramUpdatesException::pollingError($this, $reply->reason());
            }

            if ($reply->json('error_code') == 409) {
                throw TelegramUpdatesException::webhookExist($this);
            }

            /* @phpstan-ignore-next-line */
            throw TelegramUpdatesException::pollingError($this, $reply->json('description'));
        }


        /* @phpstan-ignore-next-line */
        return collect($reply->json('result'))->map(fn (array $update) => TelegramUpdate::fromArray($update));
    }

    public function setBaseUrl(string|null $url): Telegraph
    {
        return TelegraphFacade::bot($this)->setBaseUrl($url);
    }
}
