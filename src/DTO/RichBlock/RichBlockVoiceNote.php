<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockCaption;
use DefStudio\Telegraph\DTO\Voice;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockVoiceNote implements RichBlockItem, Arrayable
{
    private const TYPE = 'voice_note';
    private Voice $voiceNote;
    private ?RichBlockCaption $caption = null;

    /**
     * @param  array{
     *     type:string,
     *     voice_note:array<string, mixed>,
     *     caption?: array<string, mixed>
     * }  $data
     *
     * @return RichBlockVoiceNote
     */
    public static function fromArray(array $data): RichBlockItem
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockVoiceNote = new self();

        $richBlockVoiceNote->voiceNote = Voice::fromArray($data['voice_note']);

        if (isset($data['caption']) && $data['caption']) {
            $richBlockVoiceNote->caption = RichBlockCaption::fromArray($data['caption']);
        }

        return $richBlockVoiceNote;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function voiceNote(): Voice
    {
        return $this->voiceNote;
    }

    public function caption(): ?RichBlockCaption
    {
        return $this->caption;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'voice_note' => $this->voiceNote->toArray(),
            'caption' => $this->caption?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
