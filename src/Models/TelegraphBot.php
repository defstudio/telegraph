<?php

/** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Models;

use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Database\Eloquent\Collection;
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
class TelegraphBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'name',
    ];

    public static function booted()
    {
        self::created(function (TelegraphBot $bot) {
            if (empty($bot->name)) {
                $bot->name = "Bot #$bot->id";
                $bot->saveQuietly();
            }
        });
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
        return $this->hasMany(TelegraphChat::class, 'telegraph_bot_id');
    }

    public function registerWebhook(): Telegraph
    {
        return TelegraphFacade::bot($this)->registerWebhook();
    }

    public function getWebhookDebugInfo(): Telegraph
    {
        return TelegraphFacade::bot($this)->getWebhookDebugInfo();
    }

    public function replyWebhook(string $callbackQueryId, string $message): Telegraph
    {
        return TelegraphFacade::bot($this)->replyWebhook($callbackQueryId, $message);
    }
}
