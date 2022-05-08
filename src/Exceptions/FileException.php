<?php

namespace DefStudio\Telegraph\Exceptions;

use DefStudio\Telegraph\Telegraph;
use Exception;

final class FileException extends Exception
{
    public static function documentSizeExceeded(float $sizeInMb): FileException
    {
        return new self(sprintf("Document size (%f Mb) exceeds max allowed size of %f MB",  $sizeInMb, Telegraph::MAX_DOCUMENT_SIZE_IN_MB));
    }

    public static function thumbnailSizeExceeded(float $sizeInkb): FileException
    {
        return new self(sprintf("Thumbnail size (%f Kb) exceeds max allowed size of %f Kb",  $sizeInkb, Telegraph::MAX_THUMBNAIL_SIZE_IN_KB));
    }

    public static function thumbnailHeightExceeded(int $height): FileException
    {
        return new self(sprintf("Thumbnail height (%dpx) exceeds max allowed height of %dpx",  $height, Telegraph::MAX_THUMBNAIL_HEIGHT));
    }

    public static function thumbnailWidthExceeded(int $width): FileException
    {
        return new self(sprintf("Thumbnail width (%dpx) exceeds max allowed width of %dpx",  $width, Telegraph::MAX_THUMBNAIL_WIDTH));
    }

    public static function invalidThumbnailExtension(string $ext): FileException
    {
        return new self(sprintf("Invalid thumbnail extension (%s). Only %s are allowed",  $ext, collect(Telegraph::ALLOWED_THUMBNAIL_TYPES)->join(', ', ' and ')));
    }

    public static function fileNotFound(string $fileType, string $path): FileException
    {
        return new self("$fileType [$path] not found");
    }

    public static function photoSizeExceeded(float $sizeInMb): FileException
    {
        return new self(sprintf("Photo size (%f Mb) exceeds max allowed size of %f MB",  $sizeInMb, Telegraph::MAX_PHOTO_SIZE_IN_MB));
    }

    public static function invalidPhotoSize(int $totalLength): FileException
    {
        return new self(sprintf("Photo's sum of width and height (%dpx) exceed allowed %dpx",  $totalLength, Telegraph::MAX_PHOTO_HEIGHT_WIDTH_TOTAL));
    }

    public static function invalidPhotoRatio(float $ratio): FileException
    {
        $relativeRatio = $ratio < Telegraph::MAX_PHOTO_HEIGHT_WIDTH_RATIO ? 1 / $ratio : $ratio;

        return new self(sprintf("Ratio of height and width (%d) exceeds max allowed height of %d",  $relativeRatio, Telegraph::MAX_PHOTO_HEIGHT_WIDTH_RATIO));
    }

    public static function failedToRetreiveFileInfo(string $fileId): FileException
    {
        return new self("Failed to retreive info for file [$fileId]");
    }

    public static function unableToDownloadFile(string $fileId): FileException
    {
        return new self("An error occourred while trying to donwload file [$fileId]");
    }
}
