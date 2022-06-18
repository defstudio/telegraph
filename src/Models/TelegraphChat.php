<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpDocMissingThrowsInspection */

namespace DefStudio\Telegraph\Models;

use DefStudio\Telegraph\Database\Factories\TelegraphChatFactory;
use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * DefStudio\Telegraph\Models\TelegraphChat
 *
 * @property int $id
 * @property string $chat_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TelegraphBot $bot
 */
class TelegraphChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'name',
    ];

    protected static function newFactory(): TelegraphChatFactory
    {
        return TelegraphChatFactory::new();
    }

    public static function booted()
    {
        self::created(function (TelegraphChat $chat) {
            if (empty($chat->name)) {
                $chat->name = "Chat #$chat->id";
                $chat->saveQuietly();
            }
        });
    }

    public function bot(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(config('telegraph.models.bot'), 'telegraph_bot_id');
    }

    public function message(string $message): Telegraph
    {
        return TelegraphFacade::chat($this)->message($message);
    }

    public function html(string $message): Telegraph
    {
        return TelegraphFacade::chat($this)->html($message);
    }

    public function markdown(string $message): Telegraph
    {
        return TelegraphFacade::chat($this)->markdown($message);
    }

    public function reply(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->reply($messageId);
    }

    /**
     * @param Keyboard|callable(Keyboard):Keyboard $newKeyboard
     */
    public function replaceKeyboard(int $messageId, Keyboard|callable $newKeyboard): Telegraph
    {
        return TelegraphFacade::chat($this)->replaceKeyboard($messageId, $newKeyboard);
    }

    public function deleteKeyboard(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->deleteKeyboard($messageId);
    }

    public function edit(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->edit($messageId);
    }

    public function deleteMessage(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->deleteMessage($messageId);
    }

    public function action(string $action): Telegraph
    {
        return TelegraphFacade::chat($this)->chatAction($action);
    }

    public function document(string $path, string $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->document($path, $filename);
    }

    public function location(float $latitude, float $longitude): Telegraph
    {
        return TelegraphFacade::chat($this)->location(latitude: $latitude, longitude: $longitude);
    }

    public function photo(string $path, string $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->photo($path, $filename);
    }

    public function voice(string $path, string $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->voice($path, $filename);
    }
}
