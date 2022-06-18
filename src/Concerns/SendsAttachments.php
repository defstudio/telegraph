<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @mixin Telegraph
 */
trait SendsAttachments
{
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

        if (!File::exists($path)) {
            throw FileException::fileNotFound('Voice', $path);
        }

        $telegraph->endpoint = self::ENDPOINT_SEND_VOICE;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        $telegraph->files->put('voice', new Attachment($path, $filename));

        return $telegraph;
    }

    public function document(string $path, string $filename = null): Telegraph
    {
        $telegraph = clone $this;

        if (!File::exists($path)) {
            throw FileException::fileNotFound("Document", $path);
        }

        if (($size = $telegraph->fileSizeInMb($path)) > Telegraph::MAX_DOCUMENT_SIZE_IN_MB) {
            throw FileException::documentSizeExceeded($size);
        }

        $telegraph->endpoint = self::ENDPOINT_SEND_DOCUMENT;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        $telegraph->files->put('document', new Attachment($path, $filename));

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

        if (!File::exists($path)) {
            throw FileException::fileNotFound("Thumbnail", $path);
        }

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

    public function photo(string $path, string $filename = null): Telegraph
    {
        $telegraph = clone $this;

        if (!File::exists($path)) {
            throw FileException::fileNotFound('Photo', $path);
        }

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

        $telegraph->endpoint = self::ENDPOINT_SEND_PHOTO;

        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        $telegraph->files->put('photo', new Attachment($path, $filename));

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
}
