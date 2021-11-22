<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpDocMissingThrowsInspection */

namespace DefStudio\Telegraph\Models;

use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;
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

    public function bot(): BelongsTo
    {
        return $this->belongsTo(TelegraphBot::class, 'telegraph_bot_id');
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

    /**
     * @param array<array<array<string, string>>> $newKeyboard
     */
    public function replaceKeyboard(string $messageId, array $newKeyboard): Telegraph
    {
        return TelegraphFacade::chat($this)->replaceKeyboard($messageId, $newKeyboard);
    }

    public function deleteKeyboard(string $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->deleteKeyboard($messageId);
    }
}
