<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;

use DefStudio\Telegraph\ScopedPayloads\AnimationPayload;
use DefStudio\Telegraph\ScopedPayloads\AudioPayload;
use DefStudio\Telegraph\ScopedPayloads\DocumentPayload;
use DefStudio\Telegraph\ScopedPayloads\PhotoPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphEditMediaPayload;
use DefStudio\Telegraph\ScopedPayloads\VideoPayload;
use DefStudio\Telegraph\Telegraph;

class TelegraphEditMediaFake extends TelegraphEditMediaPayload
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

    public function animation(string $path, string $filename = null): AnimationPayload
    {
        app()->bind(AnimationPayload::class, AnimationPayloadFake::class);

        return parent::animation($path, $filename);
    }

    public function photo(string $path, string $filename = null): PhotoPayload
    {
        app()->bind(PhotoPayload::class, PhotoPayloadFake::class);

        return parent::photo($path, $filename);
    }

    public function document(string $path, string $filename = null): DocumentPayload
    {
        app()->bind(DocumentPayload::class, DocumentPayloadFake::class);

        return parent::document($path, $filename);
    }

    public function video(string $path, string $filename = null): VideoPayload
    {
        app()->bind(VideoPayload::class, VideoPayloadFake::class);

        return parent::video($path, $filename);
    }

    public function audio(string $path, string $filename = null): AudioPayload
    {
        app()->bind(AudioPayload::class, AudioPayloadFake::class);

        return parent::audio($path, $filename);
    }

    public static function assertSentEditMedia(string $type, string $media): void
    {
        self::assertSentData(Telegraph::ENDPOINT_EDIT_MEDIA, [
            "media" => json_encode([
                'type' => $type,
                'media' => $media,
            ]),
        ], false);
    }
}
