<?php

namespace DefStudio\Telegraph\Models\Concerns;

interface HasCustomUrl
{
    public function getUrl(): string;

    public function getFilesUrl(): string;
}
