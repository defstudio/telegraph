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
        if ($this->fileSizeInMb($path) > Telegraph::MAX_DOCUMENT_SIZE_IN_MB) {
            throw FileException::documentSizeExceeded($this->fileSizeInMb($path));
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
        if ($this->fileSizeInKb($path) > Telegraph::MAX_TUHMBNAIL_SIZE_IN_KB) {
            throw FileException::thumbnailSizeExceeded($this->fileSizeInKb($path));
        }

        if (Str::of(File::extension($path))->lower()->is('jpg')) {
            $this->files->put('thumb', new Attachment($path));
        }

        return $this;
    }

    protected function fileSizeInMb(string $path): float
    {
        $sizeInMBytes = $this->fileSizeInKb($path) / 1024;

        return ceil($sizeInMBytes * 100) / 100;
    }

    protected function fileSizeInKb(string $path): float
    {
        $sizeInBytes = File::size($path);
        $sizeInKBytes = $sizeInBytes / 1024;

        return ceil($sizeInKBytes * 100) / 100;
    }
}
