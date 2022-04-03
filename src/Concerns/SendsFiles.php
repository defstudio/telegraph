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
trait SendsFiles
{
    public function document(string $path, string $filename = null): Telegraph
    {
        if(!File::exists($path)){
            throw FileException::fileNotFound("Document", $path);
        }

        if (($size = $this->fileSizeInMb($path)) > Telegraph::MAX_DOCUMENT_SIZE_IN_MB) {
            throw FileException::documentSizeExceeded($size);
        }

        $this->endpoint = self::ENDPOINT_SEND_DOCUMENT;

        $this->data['chat_id'] = $this->getChat()->chat_id;

        $this->files->put('document', new Attachment($path, $filename));

        return $this;
    }

    public function withoutContentTypeDetection(): Telegraph
    {
        $this->data['disable_content_type_detection'] = 1;

        return $this;
    }

    public function thumbnail(string $path): Telegraph
    {
        if(!File::exists($path)){
            throw FileException::fileNotFound("Thumbnail", $path);
        }

        if (($size = $this->fileSizeInKb($path)) > Telegraph::MAX_THUMBNAIL_SIZE_IN_KB) {
            throw FileException::thumbnailSizeExceeded($size);
        }

        if (($height = $this->imageHeight($path)) > Telegraph::MAX_THUMBNAIL_HEIGHT) {
            throw FileException::thumbnailHeightExceeded($height);
        }

        if (($width = $this->imageWidth($path)) > Telegraph::MAX_THUMBNAIL_WIDTH) {

            throw FileException::thumbnailWidthExceeded($width);
        }

        if (!Str::of($ext = File::extension($path))->lower()->is('jpg')) {
            throw FileException::invalidThumbnailExtension($ext);
        }

        $this->files->put('thumb', new Attachment($path));

        return $this;
    }


    private function imageHeight(string $path)
    {
        return getimagesize($path)[1] ?? 0;
    }

    private function imageWidth(string $path)
    {
        return getimagesize($path)[0] ?? 0;
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
