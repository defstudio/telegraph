<?php

namespace DefStudio\Telegraph\Exceptions;

use DefStudio\Telegraph\Telegraph;
use Exception;

final class FileException extends Exception
{
    public static function documentSizeExceeded(float $sizeInMb): FileException
    {
        return new self(printf("Document size (%f Mb) exceeds max allowed size of %f MB",  $sizeInMb, Telegraph::MAX_DOCUMENT_SIZE_IN_MB));
    }

    public static function thumbnailSizeExceeded(float $sizeInkb): FileException
    {
        return new self(printf("Thumbnail size (%f Kb) exceeds max allowed size of %f Kb",  $sizeInkb, Telegraph::MAX_TUHMBNAIL_SIZE_IN_KB));
    }
}
