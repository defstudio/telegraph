<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\DTO\InputMedia;
use DefStudio\Telegraph\Telegraph;
use DefStudio\Telegraph\Validator;
use Illuminate\Support\Facades\File;

/**
 * @mixin Telegraph
 */
trait SendsAttachments
{
    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function preprocessDataSendsAttachments(array $data): array
    {
        if ($this->files->isNotEmpty() && !empty($data['text'])) {
            $data['caption'] = $data['text'];
            unset($data['text']);
        }

        if ($this->endpoint === self::ENDPOINT_EDIT_CAPTION) {
            $data['caption'] = $data['text'] ?? '';
            unset($data['text']);
        }

        if (isset($data['caption']) && empty($data['caption'])) {
            $data['caption'] = $data['text'] ?? '';
            unset($data['text']);
        }

        return $data;
    }

    public function editCaption(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_EDIT_CAPTION;
        $telegraph->data['message_id'] = $messageId;

        return $telegraph;
    }

    public function location(float $latitude, float $longitude): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_LOCATION;
        $telegraph->data['latitude'] = $latitude;
        $telegraph->data['longitude'] = $longitude;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        return $telegraph;
    }

    public function voice(string $path, string $filename = null): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_VOICE;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        if (File::exists($path)) {
            $telegraph->files->put('voice', new Attachment($path, $filename));

            return $telegraph;
        }

        $telegraph->data['voice'] = $path;
        $telegraph->data['caption'] ??= '';

        return $telegraph;
    }

    public function document(string $path, string $filename = null): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_DOCUMENT;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;


        if (File::exists($path)) {
            Validator::validateDocumentFile($path);

            $telegraph->files->put('document', new Attachment($path, $filename));

            return $telegraph;
        }

        $telegraph->data['document'] = $path;
        $telegraph->data['caption'] ??= '';

        return $telegraph;
    }

    public function withoutContentTypeDetection(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['disable_content_type_detection'] = 1;

        return $telegraph;
    }

    public function thumbnail(string $path): Telegraph
    {
        $telegraph = clone $this;

        if (File::exists($path)) {
            Validator::validateThumbFile($path);

            $telegraph->files->put('thumb', new Attachment($path));

            return $telegraph;
        }

        $telegraph->data['thumb'] = $path;
        $telegraph->data['caption'] ??= '';

        return $telegraph;
    }

    public function photo(string $path, string $filename = null): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_PHOTO;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        if (File::exists($path)) {
            Validator::validatePhotoFile($path);

            $telegraph->files->put('photo', new Attachment($path, $filename));

            return $telegraph;
        }

        $telegraph->data['photo'] = $path;
        $telegraph->data['caption'] ??= '';

        return $telegraph;
    }

    public function mediaGroup(array $mediaGroup): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_MEDIA_GROUP;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        $media = [];
        foreach ($mediaGroup as $mediaItem) {
            if (!$mediaItem instanceof InputMedia) {
                continue;
            }

            if ($mediaItem->local()) {
                $this->files->put($mediaItem->getAttachName(), $mediaItem->toAttachment());
            }
            $media[] = $mediaItem->toMediaArray();
        }

        $telegraph->data['media'] = $media;

        return $telegraph;
    }
}
