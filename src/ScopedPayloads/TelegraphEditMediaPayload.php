<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Telegraph;

class TelegraphEditMediaPayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function photo(string $path, string $filename = null): PhotoPayload
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

        return PhotoPayload::makeFrom($telegraph);
    }

    public function document(string $path, string $filename = null): DocumentPayload
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

        return DocumentPayload::makeFrom($telegraph);
    }

    public function animation(string $path, string $filename = null): AnimationPayload
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

        return AnimationPayload::makeFrom($telegraph);
    }

    public function video(string $path, string $filename = null): VideoPayload
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

        return VideoPayload::makeFrom($telegraph);
    }
}
