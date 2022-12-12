<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;

class TelegraphEditMediaPayload extends \DefStudio\Telegraph\Telegraph
{
    use BuildsFromTelegraphClass;

    public function photo(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $this->attachPhoto($telegraph, $path, $filename);

        $data = [
            'type' => 'photo',
            'media' => $telegraph->files->has('photo')
                ? "attach://photo"
                : $telegraph->data['photo'],
        ];


        $telegraph->data['media'] = json_encode($data);

        return $telegraph;
    }

    public function document(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $this->attachDocument($telegraph, $path, $filename);

        $data = [
            'type' => 'document',
            'media' => $telegraph->files->has('document')
                ? "attach://document"
                : $telegraph->data['document'],
        ];


        $telegraph->data['media'] = json_encode($data);

        return $telegraph;
    }
}
