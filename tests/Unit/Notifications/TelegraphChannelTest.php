<?php

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Notifications\TelegraphChannel;
use DefStudio\Telegraph\Notifications\TelegraphMessage;
use DefStudio\Telegraph\Telegraph as TelegraphClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

it('registers the telegraph notification channel', function () {
    expect(app(ChannelManager::class)->channel('telegraph'))->toBeInstanceOf(TelegraphChannel::class);
});

it('sends string notifications to an on-demand telegraph route', function () {
    Telegraph::fake();

    NotificationFacade::route('telegraph', [
        'bot_token' => '123456:test-token',
        'chat_id' => '-100123456789',
    ])->notify(new class () extends Notification {
        public function via(object $notifiable): array
        {
            return ['telegraph'];
        }

        public function toTelegraph(object $notifiable): string
        {
            return 'Application error detected';
        }
    });

    Telegraph::assertSentData(TelegraphClient::ENDPOINT_MESSAGE, [
        'chat_id' => '-100123456789',
        'parse_mode' => TelegraphClient::PARSE_HTML,
        'text' => 'Application error detected',
    ]);
});

it('sends telegraph message notifications through notifiable routes', function () {
    Telegraph::fake();

    $notifiable = new class () {
        public function routeNotificationForTelegraph(): array
        {
            return [
                'bot_token' => '123456:test-token',
                'chat_id' => '-100987654321',
            ];
        }
    };

    NotificationFacade::sendNow($notifiable, new class () extends Notification {
        public function via(object $notifiable): array
        {
            return ['telegraph'];
        }

        public function toTelegraph(object $notifiable): TelegraphMessage
        {
            return TelegraphMessage::make('Deploy finished')
                ->markdown()
                ->silent()
                ->withoutPreview()
                ->inThread(42);
        }
    });

    Telegraph::assertSentData(TelegraphClient::ENDPOINT_MESSAGE, [
        'chat_id' => '-100987654321',
        'disable_notification' => true,
        'disable_web_page_preview' => true,
        'message_thread_id' => 42,
        'parse_mode' => TelegraphClient::PARSE_MARKDOWN,
        'text' => 'Deploy finished',
    ]);
});

it('uses telegraph chat model routes', function () {
    Telegraph::fake();

    $chat = chat();

    $notifiable = new class ($chat) {
        public function __construct(private readonly mixed $chat)
        {
        }

        public function routeNotificationForTelegraph(): mixed
        {
            return $this->chat;
        }
    };

    NotificationFacade::sendNow($notifiable, new class () extends Notification {
        public function via(object $notifiable): array
        {
            return ['telegraph'];
        }

        public function toTelegraph(object $notifiable): string
        {
            return 'Chat model route';
        }
    });

    Telegraph::assertSentData(TelegraphClient::ENDPOINT_MESSAGE, [
        'chat_id' => '-123456789',
        'text' => 'Chat model route',
    ], exact: false);
});

it('rejects unsupported telegraph notification payloads', function () {
    Telegraph::fake();

    $notifiable = new class () {
        public function routeNotificationForTelegraph(): array
        {
            return [
                'bot_token' => '123456:test-token',
                'chat_id' => '-100123456789',
            ];
        }
    };

    expect(fn () => NotificationFacade::sendNow($notifiable, new class () extends Notification {
        public function via(object $notifiable): array
        {
            return ['telegraph'];
        }

        public function toTelegraph(object $notifiable): array
        {
            return [];
        }
    }))->toThrow(\InvalidArgumentException::class, 'toTelegraph');
});
