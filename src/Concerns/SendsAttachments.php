<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\ScopedPayloads\TelegraphEditMediaPayload;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    public function editMedia(int $messageId): TelegraphEditMediaPayload
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_EDIT_MEDIA;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['message_id'] = $messageId;

        return TelegraphEditMediaPayload::makeFrom($telegraph);
    }

    public function location(float $latitude, float $longitude): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_LOCATION;
        $telegraph->data['latitude'] = $latitude;
        $telegraph->data['longitude'] = $longitude;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        return $telegraph;
    }

    public function voice(string $path, string $filename = null): self
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

    public function document(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_DOCUMENT;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;


        $this->attachDocument($telegraph, $path, $filename);

        return $telegraph;
    }

    public function withoutContentTypeDetection(): self
    {
        $telegraph = clone $this;

        $telegraph->data['disable_content_type_detection'] = 1;

        return $telegraph;
    }

    public function thumbnail(string $path): self
    {
        $telegraph = clone $this;

        if (File::exists($path)) {
            if (($size = $telegraph->fileSizeInKb($path)) > Telegraph::MAX_THUMBNAIL_SIZE_IN_KB) {
                throw FileException::thumbnailSizeExceeded($size);
            }

            if (($height = $telegraph->imageHeight($path)) > Telegraph::MAX_THUMBNAIL_HEIGHT) {
                throw FileException::thumbnailHeightExceeded($height);
            }

            if (($width = $telegraph->imageWidth($path)) > Telegraph::MAX_THUMBNAIL_WIDTH) {
                throw FileException::thumbnailWidthExceeded($width);
            }

            if (!Str::of($ext = File::extension($path))->lower()->is('jpg')) {
                throw FileException::invalidThumbnailExtension($ext);
            }

            $telegraph->files->put('thumb', new Attachment($path));

            return $telegraph;
        }

        $telegraph->data['thumb'] = $path;
        $telegraph->data['caption'] ??= '';

        return $telegraph;
    }

    public function photo(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_PHOTO;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        $this->attachPhoto($telegraph, $path, $filename);

        return $telegraph;
    }

    private function imageHeight(string $path): int
    {
        return $this->imageDimensions($path)[1];
    }

    private function imageWidth(string $path): int
    {
        return $this->imageDimensions($path)[0];
    }

    /**
     * @return int[]
     */
    private function imageDimensions(string $path): array
    {
        $sizes = getimagesize($path);

        if (!$sizes) {
            return [0, 0];
        }

        return $sizes;
    }

    private function fileSizeInMb(string $path): float
    {
        $sizeInMBytes = $this->fileSizeInKb($path) / 1024;

        return ceil($sizeInMBytes * 100) / 100;
    }

    private function fileSizeInKb(string $path): float
    {
        $sizeInBytes = File::size($path);
        $sizeInKBytes = $sizeInBytes / 1024;

        return ceil($sizeInKBytes * 100) / 100;
    }

    public function dice(string $emoji = null): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_DICE;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        if ($emoji !== null) {
            $telegraph->data['emoji'] = $emoji;
        }

        return $telegraph;
    }

    protected function attachPhoto(self $telegraph, string $path, ?string $filename): void
    {
        if (File::exists($path)) {
            if (($size = $telegraph->fileSizeInMb($path)) > Telegraph::MAX_PHOTO_SIZE_IN_MB) {
                throw FileException::photoSizeExceeded($size);
            }

            $height = $telegraph->imageHeight($path);
            $width = $telegraph->imageWidth($path);

            if (($totalLength = $height + $width) > Telegraph::MAX_PHOTO_HEIGHT_WIDTH_TOTAL) {
                throw FileException::invalidPhotoSize($totalLength);
            }

            if (($ratio = $height / $width) > Telegraph::MAX_PHOTO_HEIGHT_WIDTH_RATIO || $ratio < (1 / Telegraph::MAX_PHOTO_HEIGHT_WIDTH_RATIO)) {
                throw FileException::invalidPhotoRatio($ratio);
            }

            $telegraph->files->put('photo', new Attachment($path, $filename));
        } else {
            $telegraph->data['photo'] = $path;
            $telegraph->data['caption'] ??= '';
        }
    }

    protected function attachDocument(self $telegraph, string $path,  ?string $filename): void
    {
        if (File::exists($path)) {
            if (($size = $telegraph->fileSizeInMb($path)) > Telegraph::MAX_DOCUMENT_SIZE_IN_MB) {
                throw FileException::documentSizeExceeded($size);
            }

            $telegraph->files->put('document', new Attachment($path, $filename));
        } else {
            $telegraph->data['document'] = $path;
            $telegraph->data['caption'] ??= '';
        }
    }
}
