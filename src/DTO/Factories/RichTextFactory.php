<?php

namespace DefStudio\Telegraph\DTO\Factories;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\RichText\RichTextAnchor;
use DefStudio\Telegraph\DTO\RichText\RichTextAnchorLink;
use DefStudio\Telegraph\DTO\RichText\RichTextBankCardNumber;
use DefStudio\Telegraph\DTO\RichText\RichTextBold;
use DefStudio\Telegraph\DTO\RichText\RichTextBotCommand;
use DefStudio\Telegraph\DTO\RichText\RichTextCashtag;
use DefStudio\Telegraph\DTO\RichText\RichTextCode;
use DefStudio\Telegraph\DTO\RichText\RichTextCustomEmoji;
use DefStudio\Telegraph\DTO\RichText\RichTextDateTime;
use DefStudio\Telegraph\DTO\RichText\RichTextEmailAddress;
use DefStudio\Telegraph\DTO\RichText\RichTextHashtag;
use DefStudio\Telegraph\DTO\RichText\RichTextItalic;
use DefStudio\Telegraph\DTO\RichText\RichTextMarked;
use DefStudio\Telegraph\DTO\RichText\RichTextMathematicalExpression;
use DefStudio\Telegraph\DTO\RichText\RichTextMention;
use DefStudio\Telegraph\DTO\RichText\RichTextPhoneNumber;
use DefStudio\Telegraph\DTO\RichText\RichTextReference;
use DefStudio\Telegraph\DTO\RichText\RichTextReferenceLink;
use DefStudio\Telegraph\DTO\RichText\RichTextSpoiler;
use DefStudio\Telegraph\DTO\RichText\RichTextStrikethrough;
use DefStudio\Telegraph\DTO\RichText\RichTextString;
use DefStudio\Telegraph\DTO\RichText\RichTextSubscript;
use DefStudio\Telegraph\DTO\RichText\RichTextSuperscript;
use DefStudio\Telegraph\DTO\RichText\RichTextTextMention;
use DefStudio\Telegraph\DTO\RichText\RichTextUnderline;
use DefStudio\Telegraph\DTO\RichText\RichTextUrl;
use DefStudio\Telegraph\Exceptions\RichTextException;
use DefStudio\Telegraph\Exceptions\RichTextFactoryException;
use Illuminate\Support\Collection;

class RichTextFactory
{
    /**
     *
     * @param  string|array<string, mixed>  $data
     *
     * @return RichTextItem|Collection<int|string,RichTextItem>
     * @throws RichTextException
     * @throws RichTextFactoryException
     */
    public function fromData(string|array $data): RichTextItem|Collection
    {
        if (is_string($data)) {
            return RichTextString::fromData($data);
        }

        if (!isset($data['type'])) {
            /** @phpstan-ignore-next-line  */
            return collect($data)->map(fn (string|array $item) => (is_array($item) && !isset($item['type'])) ? throw RichTextFactoryException::structureMismatch() : $this->fromData($item));
        }

        return match ($data['type']) {
            app(RichTextBold::class)->type() => RichTextBold::fromData($data), //@phpstan-ignore-line
            app(RichTextItalic::class)->type() => RichTextItalic::fromData($data), //@phpstan-ignore-line
            app(RichTextUnderline::class)->type() => RichTextUnderline::fromData($data), //@phpstan-ignore-line
            app(RichTextStrikethrough::class)->type() => RichTextStrikethrough::fromData($data), //@phpstan-ignore-line
            app(RichTextSpoiler::class)->type() => RichTextSpoiler::fromData($data), //@phpstan-ignore-line
            app(RichTextDateTime::class)->type() => RichTextDateTime::fromData($data), //@phpstan-ignore-line
            app(RichTextTextMention::class)->type() => RichTextTextMention::fromData($data), //@phpstan-ignore-line
            app(RichTextSubscript::class)->type() => RichTextSubscript::fromData($data), //@phpstan-ignore-line
            app(RichTextSuperscript::class)->type() => RichTextSuperscript::fromData($data), //@phpstan-ignore-line
            app(RichTextMarked::class)->type() => RichTextMarked::fromData($data), //@phpstan-ignore-line
            app(RichTextCode::class)->type() => RichTextCode::fromData($data), //@phpstan-ignore-line
            app(RichTextCustomEmoji::class)->type() => RichTextCustomEmoji::fromData($data), //@phpstan-ignore-line
            app(RichTextMathematicalExpression::class)->type() => RichTextMathematicalExpression::fromData($data), //@phpstan-ignore-line
            app(RichTextUrl::class)->type() => RichTextUrl::fromData($data), //@phpstan-ignore-line
            app(RichTextEmailAddress::class)->type() => RichTextEmailAddress::fromData($data), //@phpstan-ignore-line
            app(RichTextPhoneNumber::class)->type() => RichTextPhoneNumber::fromData($data), //@phpstan-ignore-line
            app(RichTextBankCardNumber::class)->type() => RichTextBankCardNumber::fromData($data), //@phpstan-ignore-line
            app(RichTextMention::class)->type() => RichTextMention::fromData($data), //@phpstan-ignore-line
            app(RichTextHashtag::class)->type() => RichTextHashtag::fromData($data), //@phpstan-ignore-line
            app(RichTextCashtag::class)->type() => RichTextCashtag::fromData($data), //@phpstan-ignore-line
            app(RichTextBotCommand::class)->type() => RichTextBotCommand::fromData($data), //@phpstan-ignore-line
            app(RichTextAnchor::class)->type() => RichTextAnchor::fromData($data), //@phpstan-ignore-line
            app(RichTextAnchorLink::class)->type() => RichTextAnchorLink::fromData($data), //@phpstan-ignore-line
            app(RichTextReference::class)->type() => RichTextReference::fromData($data), //@phpstan-ignore-line
            app(RichTextReferenceLink::class)->type() => RichTextReferenceLink::fromData($data), //@phpstan-ignore-line

            default => throw RichTextFactoryException::invalidType($data['type'])
        };
    }
}
