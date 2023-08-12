<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Contracts\Downloadable;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait StoresFiles
{
    public function getFileInfo(string $fileId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_FILE;
        $telegraph->data['file_id'] = $fileId;

        return $telegraph;
    }

    public function store(Downloadable|string $downloadable, string $path, string $filename = null): string
    {
        $fileId = is_string($downloadable) ? $downloadable : $downloadable->id();

        $response = $this->getFileInfo($fileId)->send();

        if ($response->telegraphError()) {
            throw FileException::failedToRetreiveFileInfo($fileId);
        }

        $filePath = $response->json('result.file_path');

        assert(is_string($filePath));

        $url = Str::of($this->getFilesBaseUrl())
            ->append($this->getBotToken())
            ->append('/', $filePath);

        /** @var Response $response */
        $response = Http::get($url);

        if ($response->failed()) {
            throw FileException::unableToDownloadFile($fileId);
        }

        $filename ??= $url->afterLast('/')->before('?');

        File::ensureDirectoryExists($path);
        File::put($path . "/" . $filename, $response->body());

        return $path . "/" . $filename;
    }
}
