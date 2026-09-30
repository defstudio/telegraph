<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\Notifications;

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class TelegraphRoute
{
    public static function forNotifiable(object $notifiable, Notification $notification): mixed
    {
        if (method_exists($notifiable, 'routeNotificationFor')) {
            return $notifiable->routeNotificationFor('telegraph', $notification);
        }
        if (method_exists($notifiable, 'routeNotificationForTelegraph')) {
            return $notifiable->routeNotificationForTelegraph($notification);
        }

        return null;
    }

    public static function apply(Telegraph $telegraph, mixed $route): Telegraph
    {
        if ($route === null) {
            return $telegraph;
        }
        if ($route instanceof TelegraphChat || is_string($route)) {
            return $telegraph->chat($route);
        }
        if ($route instanceof TelegraphBot) {
            return $telegraph->bot($route);
        }
        if (is_array($route)) {
            return self::applyArrayRoute($telegraph, $route);
        }

        throw new \InvalidArgumentException('Telegraph notification route must be a chat id, TelegraphChat, TelegraphBot, or an array with chat and optional bot values.');
    }

    /**
     * @param  array<mixed>  $route
     */
    private static function applyArrayRoute(Telegraph $telegraph, array $route): Telegraph
    {
        $bot = Arr::get($route, 'bot', Arr::get($route, 'bot_token'));
        $chat = Arr::get($route, 'chat', Arr::get($route, 'chat_id'));

        if ($bot !== null) {
            if (!$bot instanceof TelegraphBot && !is_string($bot)) {
                throw new \InvalidArgumentException('Telegraph notification route [bot] value must be a bot token or TelegraphBot instance.');
            }

            $telegraph = $telegraph->bot($bot);
        }

        if ($chat !== null) {
            if (!$chat instanceof TelegraphChat && !is_string($chat)) {
                throw new \InvalidArgumentException('Telegraph notification route [chat] value must be a chat id or TelegraphChat instance.');
            }

            $telegraph = $telegraph->chat($chat);
        }

        return $telegraph;
    }
}
