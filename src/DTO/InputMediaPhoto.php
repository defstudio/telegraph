<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Exceptions\InputMediaException;
use DefStudio\Telegraph\Telegraph;
use DefStudio\Telegraph\Validator;

class InputMediaPhoto extends InputMedia
{
    public function html(string $message = null): static
    {
        $this->parseMode = Telegraph::PARSE_HTML;
        $this->caption = $message;

        return $this;
    }

    public function markdown(string $message = null): static
    {
        $this->parseMode = Telegraph::PARSE_MARKDOWN;
        $this->caption = $message;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function toMediaArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'media' => $this->attachment()->media(),
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode,
        ]);
    }

    /**
     * @throws FileException
     * @throws InputMediaException
     */
    protected function validate(): void
    {
        Validator::validatePhoto($this->contents);
    }
}
