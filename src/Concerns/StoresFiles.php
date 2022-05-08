<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Contracts\Downloadable;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait StoresFiles
{
    public function getFileInfo(string $file_id): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_FILE;
        $telegraph->data['file_id'] = $file_id;

        return $telegraph;
    }

    public function store(Downloadable|string $attachment, string $path, string $filename = null): string
    {
        $attachment_id = is_string($attachment) ? $attachment : $attachment->id();

        $response = $this->getFileInfo($attachment_id)->send();

        if ($response->telegraphError()) {
            throw FileException::failedToRetreiveFileInfo($attachment_id);
        }

        $filePath = $response->json('result.file_path');

        $url = Str::of(self::TELEGRAM_API_FILE_BASE_URL)
            ->append($this->getBot()->token)
            ->append('/', $filePath);

        $content = file_get_contents($url);

        if (!$content) {
            throw FileException::unableToDownloadFile($attachment_id);
        }

        $filename ??= $url->afterLast('/')->before('?');

        File::put($path . "/" . $filename, $content);

        return $path . "/" . $filename;
    }
}
