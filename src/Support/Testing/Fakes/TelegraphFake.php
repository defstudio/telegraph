<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;
use DefStudio\Telegraph\ScopedPayloads\AnimationPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphPollPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphQuizPayload;
use DefStudio\Telegraph\Telegraph;
use PHPUnit\Framework\Assert;

class TelegraphFake extends Telegraph
{
    use FakesRequests;

    /**
     * @param array<string, array<mixed>> $replies
     */
    public function __construct(array $replies = [])
    {
        parent::__construct();
        $this->replies = $replies;
    }

    public function editMedia(int $messageId): TelegraphEditMediaFake
    {
        $fake = new TelegraphEditMediaFake($this->replies);
        $fake->endpoint = self::ENDPOINT_EDIT_MEDIA;
        $fake->data['message_id'] = $messageId;

        return $fake;
    }

    public function animation(string $path, string $filename = null): AnimationPayload
    {
        app()->bind(AnimationPayload::class, AnimationPayloadFake::class);

        return parent::animation($path, $filename);
    }

    public function poll(string $question): TelegraphPollPayload
    {
        $fake = new TelegraphPollFake($this->replies);
        $fake->endpoint = self::ENDPOINT_SEND_POLL;
        $fake->data['options'] = [];
        $fake->data['question'] = $question;

        return $fake;
    }

    public function quiz(string $question): TelegraphQuizPayload
    {
        $fake = new TelegraphQuizFake($this->replies);
        $fake->endpoint = self::ENDPOINT_SEND_POLL;
        $fake->data['options'] = [];
        $fake->data['question'] = $question;
        $fake->data['type'] = 'quiz';

        return $fake;
    }

    public function assertStoredFile(string $fileId): void
    {
        $downloadedFiles = collect(self::$downloadedFiles)
            ->filter(fn ($downloadedFileId) => $fileId === $downloadedFileId);

        Assert::assertNotEmpty($downloadedFiles->toArray(), sprintf("Failed to assert that a file with id [%s] was stored (%d files stored so fare)", $fileId, count(self::$downloadedFiles)));
    }

    public function assertSent(string $message, bool $exact = true): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_MESSAGE, [
            'text' => $message,
        ], $exact);
    }

    public function assertNothingSent(): void
    {
        Assert::assertEmpty(self::$sentMessages, sprintf("Failed to assert that no request were sent (sent %d requests so far)", count(self::$sentMessages)));
    }

    public function assertRegisteredWebhook(): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_SET_WEBHOOK);
    }

    public function assertUnregisteredWebhook(): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_UNSET_WEBHOOK);
    }

    public function assertRequestedWebhookDebugInfo(): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_GET_WEBHOOK_DEBUG_INFO);
    }

    public function assertRepliedWebhook(string $message): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_ANSWER_WEBHOOK, [
            'text' => $message,
        ]);
    }

    public function assertRepliedWebhookIsAlert(): void
    {
        $this->assertSentData(Telegraph::ENDPOINT_ANSWER_WEBHOOK, [
            'show_alert' => true,
        ]);
    }
}
