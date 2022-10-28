<?php

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Exceptions\InputMediaException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Validator
{
    /**
     * @throws FileException
     * @throws InputMediaException
     */
    public static function validatePhoto(string $path): void
    {
        if (File::exists($path)) {
            static::validatePhotoFile($path);
        }

        // check path is valid url or fileID
        if (
            !filter_var($path, FILTER_VALIDATE_URL)
            && !(preg_match('/^[\w\-]{20,}+$/u', trim($path)) > 0)
        ) {
            throw InputMediaException::undefinedFormat();
        }
    }

    /**
     * @throws FileException
     */
    public static function validatePhotoFile(string $path): void
    {
        if (($size = static::fileSizeInMb($path)) > Telegraph::MAX_PHOTO_SIZE_IN_MB) {
            throw FileException::photoSizeExceeded($size);
        }

        $height = static::imageHeight($path);
        $width = static::imageWidth($path);

        if (($totalLength = $height + $width) > Telegraph::MAX_PHOTO_HEIGHT_WIDTH_TOTAL) {
            throw FileException::invalidPhotoSize($totalLength);
        }

        if (($ratio = $height / $width) > Telegraph::MAX_PHOTO_HEIGHT_WIDTH_RATIO || $ratio < (1 / Telegraph::MAX_PHOTO_HEIGHT_WIDTH_RATIO)) {
            throw FileException::invalidPhotoRatio($ratio);
        }
    }

    /**
     * @throws FileException
     */
    public static function validateDocumentFile(string $path): void
    {
        if (($size = static::fileSizeInMb($path)) > Telegraph::MAX_DOCUMENT_SIZE_IN_MB) {
            throw FileException::documentSizeExceeded($size);
        }
    }
    /**
     * @throws FileException
     */
    public static function validateThumbFile(string $path): void
    {
        if (($size = static::fileSizeInKb($path)) > Telegraph::MAX_THUMBNAIL_SIZE_IN_KB) {
            throw FileException::thumbnailSizeExceeded($size);
        }

        if (($height = static::imageHeight($path)) > Telegraph::MAX_THUMBNAIL_HEIGHT) {
            throw FileException::thumbnailHeightExceeded($height);
        }

        if (($width = static::imageWidth($path)) > Telegraph::MAX_THUMBNAIL_WIDTH) {
            throw FileException::thumbnailWidthExceeded($width);
        }

        if (!Str::of($ext = File::extension($path))->lower()->is('jpg')) {
            throw FileException::invalidThumbnailExtension($ext);
        }
    }

    protected static function imageHeight(string $path): int
    {
        return static::imageDimensions($path)[1];
    }

    protected static function imageWidth(string $path): int
    {
        return static::imageDimensions($path)[0];
    }

    /**
     * @return int[]
     */
    protected static function imageDimensions(string $path): array
    {
        $sizes = getimagesize($path);

        if (!$sizes) {
            return [0, 0];
        }

        return $sizes;
    }

    protected static function fileSizeInMb(string $path): float
    {
        $sizeInMBytes = static::fileSizeInKb($path) / 1024;

        return ceil($sizeInMBytes * 100) / 100;
    }

    protected static function fileSizeInKb(string $path): float
    {
        $sizeInBytes = File::size($path);
        $sizeInKBytes = $sizeInBytes / 1024;

        return ceil($sizeInKBytes * 100) / 100;
    }
}
