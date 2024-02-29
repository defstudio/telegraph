<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class FileException extends Exception
{
    public static function documentSizeExceeded(float $sizeMb, float $maxSizeMb): FileException
    {
        return new self(sprintf("Document size (%f Mb) exceeds max allowed size of %f MB",  $sizeMb, $maxSizeMb));
    }

    public static function thumbnailSizeExceeded(float $sizeKb, float $maxSizeKb): FileException
    {
        return new self(sprintf("Thumbnail size (%f Kb) exceeds max allowed size of %f Kb",  $sizeKb, $maxSizeKb));
    }

    public static function thumbnailHeightExceeded(int $height, int $maxHeigth): FileException
    {
        return new self(sprintf("Thumbnail height (%dpx) exceeds max allowed height of %dpx",  $height, $maxHeigth));
    }

    public static function thumbnailWidthExceeded(int $width, int $maxWidth): FileException
    {
        return new self(sprintf("Thumbnail width (%dpx) exceeds max allowed width of %dpx",  $width, $maxWidth));
    }

    /**
     * @param string[] $allowedExt
     */
    public static function invalidThumbnailExtension(string $ext, array $allowedExt): FileException
    {
        return new self(sprintf("Invalid thumbnail extension (%s). Only %s are allowed",  $ext, collect($allowedExt)->join(', ', ' and ')));
    }

    public static function fileNotFound(string $fileType, string $path): FileException
    {
        return new self("$fileType [$path] not found");
    }

    public static function photoSizeExceeded(float $sizeMb, float $maxSizeMb): FileException
    {
        return new self(sprintf("Photo size (%f Mb) exceeds max allowed size of %f MB",  $sizeMb, $maxSizeMb));
    }

    public static function invalidPhotoSize(int $totalLength, int $maxTotalLength): FileException
    {
        return new self(sprintf("Photo's sum of width and height (%dpx) exceed allowed %dpx",  $totalLength, $maxTotalLength));
    }

    public static function invalidPhotoRatio(float $ratio, float $maxRatio): FileException
    {
        $relativeRatio = $ratio < $maxRatio ? 1 / $ratio : $ratio;

        return new self(sprintf("Ratio of height and width (%f) exceeds max allowed ratio of %f",  $relativeRatio, $maxRatio));
    }

    public static function failedToRetreiveFileInfo(string $fileId): FileException
    {
        return new self("Failed to retreive info for file [$fileId]");
    }

    public static function unableToDownloadFile(string $fileId): FileException
    {
        return new self("An error occourred while trying to download file [$fileId]");
    }
}
