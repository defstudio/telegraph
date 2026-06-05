<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\Notifications;

use DefStudio\Telegraph\Client\TelegraphResponse;
use Illuminate\Notifications\Notification;

class TelegraphChannel
{
    public function send(object $notifiable, Notification $notification): ?TelegraphResponse
    {
        if (!method_exists($notification, 'toTelegraph')) {
            return null;
        }

        $message = $notification->toTelegraph($notifiable);
        if ($message === null) {
            return null;
        }
        if (is_string($message)) {
            $message = TelegraphMessage::make($message);
        }
        if (!$message instanceof TelegraphMessage) {
            throw new \InvalidArgumentException('Telegraph notifications must return a string, TelegraphMessage, or null from toTelegraph().');
        }

        return $message->send(TelegraphRoute::forNotifiable($notifiable, $notification));
    }
}
