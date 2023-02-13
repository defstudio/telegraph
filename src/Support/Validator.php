<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\Telegraph\Support;

use DefStudio\Telegraph\Exceptions\ArgumentException;

class Validator
{
    public static function validateMenuButtonParameters(array $data): array
    {
        if (!isset($data["type"])) {
            throw ArgumentException::missing("type");
        }

        if ($data["type"] == "web_app") {
            if (!isset($data["text"])) {
                throw ArgumentException::missing("text");
            }

            if (!isset($data["url"])) {
                throw ArgumentException::missing("url");
            }

            $data["web_app"]["url"] = $data["url"];
            unset($data["url"]);
        }

        return $data;
    }
}
