<?php

namespace DefStudio\Telegraph\DTO\Factories;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockAnchor;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockAnimation;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockAudio;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockBlockQuotation;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockCollage;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockDetails;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockDivider;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockFooter;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockList;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockMap;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockMathematicalExpression;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockParagraph;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockPhoto;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockPreformatted;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockPullQuotation;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockSectionHeading;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockSlideshow;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockTable;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockThinking;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockVideo;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockVoiceNote;
use DefStudio\Telegraph\Exceptions\RichBlockFactoryException;

class RichBlockFactory
{
    /**
     * @param  array{
     *     type?:string
     * }  $blockData
     *
     * @return RichBlockItem
     * @throws RichBlockFactoryException
     */
    public function fromArray(array $blockData): RichBlockItem
    {
        if (!isset($blockData['type'])) {
            throw RichBlockFactoryException::missingType();
        }

        return match ($blockData['type']) {
            app(RichBlockAnchor::class)->type() => RichBlockAnchor::fromArray($blockData),
            app(RichBlockAnimation::class)->type() => RichBlockAnimation::fromArray($blockData),
            app(RichBlockAudio::class)->type() => RichBlockAudio::fromArray($blockData),
            app(RichBlockBlockQuotation::class)->type() => RichBlockBlockQuotation::fromArray($blockData),
            app(RichBlockCollage::class)->type() => RichBlockCollage::fromArray($blockData),
            app(RichBlockDetails::class)->type() => RichBlockDetails::fromArray($blockData),
            app(RichBlockDivider::class)->type() => RichBlockDivider::fromArray($blockData),
            app(RichBlockFooter::class)->type() => RichBlockFooter::fromArray($blockData),
            app(RichBlockList::class)->type() => RichBlockList::fromArray($blockData),
            app(RichBlockMap::class)->type() => RichBlockMap::fromArray($blockData),
            app(RichBlockMathematicalExpression::class)->type() => RichBlockMathematicalExpression::fromArray($blockData),
            app(RichBlockParagraph::class)->type() => RichBlockParagraph::fromArray($blockData),
            app(RichBlockPhoto::class)->type() => RichBlockPhoto::fromArray($blockData),
            app(RichBlockPreformatted::class)->type() => RichBlockPreformatted::fromArray($blockData),
            app(RichBlockPullQuotation::class)->type() => RichBlockPullQuotation::fromArray($blockData),
            app(RichBlockSectionHeading::class)->type() => RichBlockSectionHeading::fromArray($blockData),
            app(RichBlockSlideshow::class)->type() => RichBlockSlideshow::fromArray($blockData),
            app(RichBlockTable::class)->type() => RichBlockTable::fromArray($blockData),
            app(RichBlockThinking::class)->type() => RichBlockThinking::fromArray($blockData),
            app(RichBlockVideo::class)->type() => RichBlockVideo::fromArray($blockData),
            app(RichBlockVoiceNote::class)->type() => RichBlockVoiceNote::fromArray($blockData),
            default => throw RichBlockFactoryException::invalidType($blockData['type'])
        };
    }
}
