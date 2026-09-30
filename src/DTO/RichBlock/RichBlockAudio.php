<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\Audio;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockCaption;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockAudio implements RichBlockItem, Arrayable
{
    private const TYPE = 'audio';
    private Audio $audio;
    private ?RichBlockCaption $caption = null;

    /**
     * @param  array{
     *     type:string,
     *     audio: array<string, mixed>,
     *     caption?: array<string, mixed>
     * }  $data
     *
     * @return RichBlockAudio
     */
    public static function fromArray(array $data): RichBlockAudio
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockAudio = new self();

        $richBlockAudio->audio = Audio::fromArray($data['audio']);

        if (isset($data['caption']) && $data['caption']) {
            $richBlockAudio->caption = RichBlockCaption::fromArray($data['caption']);
        }

        return $richBlockAudio;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function audio(): Audio
    {
        return $this->audio;
    }

    public function caption(): ?RichBlockCaption
    {
        return $this->caption;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'audio' => $this->audio->toArray(),
            'caption' => $this->caption?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
