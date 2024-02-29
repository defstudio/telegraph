<?php

/** @noinspection DuplicatedCode */

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

        $telegraph->data['chat_id'] = $telegraph->getChatId();
        $telegraph->data['message_id'] = $messageId;

        return TelegraphEditMediaPayload::makeFrom($telegraph);
    }

    public function location(float $latitude, float $longitude): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_LOCATION;
        $telegraph->data['latitude'] = $latitude;
        $telegraph->data['longitude'] = $longitude;
        $telegraph->data['chat_id'] = $telegraph->getChatId();

        return $telegraph;
    }

    public function contact(string $phoneNumber, string $firstName): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_CONTACT;
        $telegraph->data['chat_id'] = $telegraph->getChatId();
        $telegraph->data['phone_number'] = $phoneNumber;
        $telegraph->data['first_name'] = $firstName;

        return $telegraph;
    }

    public function voice(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_VOICE;

        $telegraph->data['chat_id'] = $telegraph->getChatId();

        if (File::exists($path)) {
            $telegraph->files->put('voice', new Attachment($path, $filename));

            return $telegraph;
        }

        $telegraph->data['voice'] = $path;
        $telegraph->data['caption'] ??= '';

        return $telegraph;
    }

    public function animation(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_ANIMATION;

        $telegraph->data['chat_id'] = $telegraph->getChatId();


        $this->attachAnimation($telegraph, $path, $filename);

        return $telegraph;
    }

    public function video(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_VIDEO;

        $telegraph->data['chat_id'] = $telegraph->getChatId();


        $this->attachVideo($telegraph, $path, $filename);

        return $telegraph;
    }

    public function audio(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_AUDIO;

        $telegraph->data['chat_id'] = $telegraph->getChatId();


        $this->attachAudio($telegraph, $path, $filename);

        return $telegraph;
    }

    public function document(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_DOCUMENT;

        $telegraph->data['chat_id'] = $telegraph->getChatId();


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
            /* @phpstan-ignore-next-line  */
            $maxSizeKb = floatval(config('telegraph.attachments.thumbnail.max_size_kb', 200));

            if (($size = $telegraph->fileSizeInKb($path)) > $maxSizeKb) {
                throw FileException::thumbnailSizeExceeded($size, $maxSizeKb);
            }

            $maxHeight = config('telegraph.attachments.thumbnail.max_height_px', 320);

            assert(is_integer($maxHeight));

            if (($height = $telegraph->imageHeight($path)) > $maxHeight) {
                throw FileException::thumbnailHeightExceeded($height, $maxHeight);
            }

            $maxWidth = config('telegraph.attachments.thumbnail.max_width_px', 320);

            assert(is_integer($maxWidth));

            if (($width = $telegraph->imageWidth($path)) > $maxWidth) {
                throw FileException::thumbnailWidthExceeded($width, $maxWidth);
            }

            $allowedExt = config('telegraph.attachments.thumbnail.allowed_ext', ['jpg']);

            assert(is_array($allowedExt));

            if (!Str::of($ext = File::extension($path))->lower()->is($allowedExt)) {
                throw FileException::invalidThumbnailExtension($ext, $allowedExt);
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

        $telegraph->data['chat_id'] = $telegraph->getChatId();

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

        return [$sizes[0], $sizes[1]];
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
        $telegraph->data['chat_id'] = $telegraph->getChatId();

        if ($emoji !== null) {
            $telegraph->data['emoji'] = $emoji;
        }

        return $telegraph;
    }

    protected function attachPhoto(self $telegraph, string $path, ?string $filename): void
    {
        if (File::exists($path)) {
            /* @phpstan-ignore-next-line  */
            $maxSizeInMb = floatval(config('telegraph.attachments.photo.max_size_mb', 10));

            if (($size = $telegraph->fileSizeInMb($path)) > $maxSizeInMb) {
                throw FileException::photoSizeExceeded($size, $maxSizeInMb);
            }

            $height = $telegraph->imageHeight($path);
            $width = $telegraph->imageWidth($path);

            $heightWidthSumPx = config('telegraph.attachments.photo.height_width_sum_px', 10000);

            assert(is_integer($heightWidthSumPx));

            if (($totalLength = $height + $width) > $heightWidthSumPx) {
                throw FileException::invalidPhotoSize($totalLength, $heightWidthSumPx);
            }

            /* @phpstan-ignore-next-line  */
            $maxRatio = floatval(config('telegraph.attachments.photo.max_ratio', 20));

            if (($ratio = $height / $width) > $maxRatio || $ratio < (1 / $maxRatio)) {
                throw FileException::invalidPhotoRatio($ratio, $maxRatio);
            }

            $telegraph->files->put('photo', new Attachment($path, $filename));
        } else {
            $telegraph->data['photo'] = $path;
            $telegraph->data['caption'] ??= '';
        }
    }

    protected function attachAnimation(self $telegraph, string $path, ?string $filename): void
    {
        if (File::exists($path)) {
            /* @phpstan-ignore-next-line  */
            $maxSizeMb = floatval(config('telegraph.attachments.animation.max_size_mb', 50));

            if (($size = $telegraph->fileSizeInMb($path)) > $maxSizeMb) {
                throw FileException::documentSizeExceeded($size, $maxSizeMb);
            }

            $telegraph->files->put('animation', new Attachment($path, $filename));
        } else {
            $telegraph->data['animation'] = $path;
            $telegraph->data['duration'] ??= '';
            $telegraph->data['width'] ??= '';
            $telegraph->data['height'] ??= '';
            $telegraph->data['caption'] ??= '';
        }
    }

    protected function attachVideo(self $telegraph, string $path, ?string $filename): void
    {
        if (File::exists($path)) {
            /* @phpstan-ignore-next-line  */
            $maxSizeMb = floatval(config('telegraph.attachments.video.max_size_mb', 50));

            if (($size = $telegraph->fileSizeInMb($path)) > $maxSizeMb) {
                throw FileException::documentSizeExceeded($size, $maxSizeMb);
            }

            $telegraph->files->put('video', new Attachment($path, $filename));
        } else {
            $telegraph->data['video'] = $path;
            $telegraph->data['duration'] ??= '';
            $telegraph->data['width'] ??= '';
            $telegraph->data['height'] ??= '';
            $telegraph->data['thumb'] ??= '';
            $telegraph->data['caption'] ??= '';
            $telegraph->data['parse_mode'] ??= '';
            $telegraph->data['supports_streaming'] ??= 'false';
            $telegraph->data['disable_notification'] ??= 'false';
            $telegraph->data['protect_content'] ??= 'false';
            $telegraph->data['reply_to_message_id'] ??= '';
            $telegraph->data['allow_sending_without_reply'] ??= 'false';
        }
    }

    protected function attachAudio(self $telegraph, string $path, ?string $filename): void
    {
        if (File::exists($path)) {
            /* @phpstan-ignore-next-line  */
            $maxSizeMb = floatval(config('telegraph.attachments.audio.max_size_mb', 50));

            if (($size = $telegraph->fileSizeInMb($path)) > $maxSizeMb) {
                throw FileException::documentSizeExceeded($size, $maxSizeMb);
            }

            $telegraph->files->put('audio', new Attachment($path, $filename));
        } else {
            $telegraph->data['audio'] = $path;
            $telegraph->data['duration'] ??= '';
            $telegraph->data['thumb'] ??= '';
            $telegraph->data['caption'] ??= '';
            $telegraph->data['parse_mode'] ??= '';
            $telegraph->data['disable_notification'] ??= 'false';
            $telegraph->data['protect_content'] ??= 'false';
            $telegraph->data['reply_to_message_id'] ??= '';
            $telegraph->data['allow_sending_without_reply'] ??= 'false';
        }
    }

    protected function attachDocument(self $telegraph, string $path, ?string $filename): void
    {
        if (File::exists($path)) {

            /* @phpstan-ignore-next-line  */
            $maxSizeMb = floatval(config('telegraph.attachments.document.max_size_mb', 50));

            if (($size = $telegraph->fileSizeInMb($path)) > $maxSizeMb) {
                throw FileException::documentSizeExceeded($size, $maxSizeMb);
            }

            $telegraph->files->put('document', new Attachment($path, $filename));
        } else {
            $telegraph->data['document'] = $path;
            $telegraph->data['caption'] ??= '';
        }
    }
}
