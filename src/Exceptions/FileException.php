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
        return new self(sprintf("Thumbnail size (%f Kb) exceeds max allowed size of %f Kb",  $sizeInkb, Telegraph::MAX_TUHMBNAIL_SIZE_IN_KB));
    }
}
