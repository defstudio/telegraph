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

    public function animation(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $this->attachAnimation($telegraph, $path, $filename);

        $data = [
            'type' => 'animation',
            'media' => $telegraph->files->has('animation')
                ? "attach://animation"
                : $telegraph->data['animation'],
        ];


        $telegraph->data['media'] = json_encode($data);

        return $telegraph;
    }

    public function video(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $this->attachVideo($telegraph, $path, $filename);

        $data = [
            'type' => 'video',
            'media' => $telegraph->files->has('video')
                ? "attach://video"
                : $telegraph->data['video'],
        ];


        $telegraph->data['media'] = json_encode($data);

        return $telegraph;
    }

    public function audio(string $path, string $filename = null): self
    {
        $telegraph = clone $this;

        $this->attachAudio($telegraph, $path, $filename);

        $data = [
            'type' => 'audio',
            'media' => $telegraph->files->has('audio')
                ? "attach://audio"
                : $telegraph->data['audio'],
        ];


        $telegraph->data['media'] = json_encode($data);

        return $telegraph;
    }
}
