<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Invoice implements Arrayable
{
    /**
     * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     */
    public int|string $chatId;

    /**
     * Product name, 1-32 characters
     */
    public string $title;

    /**
     * Product description, 1-255 characters
     */
    public string $description;

    /**
     * Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use for your internal processes.
     */
    public string $payload;

    /**
     * Payment provider token, obtained via @BotFather
     */
    public string $providerToken;

    /**
     *    Three-letter ISO 4217 currency code
     */
    public string $currency;

    /**
     * Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.)
     *
     * @var array|LabeledPrice[]
     */
    public array $prices;

    /**
     * Unique deep-linking parameter. If left empty, forwarded copies of the sent message will have a Pay button, allowing multiple users to pay directly from the forwarded message, using the same invoice. If non-empty, forwarded copies of the sent message will have a URL button with a deep link to the bot (instead of a Pay button), with the value used as the start parameter
     */
    public string $startParameter = 'payment';

    /**
     * JSON-serialized data about the invoice, which will be shared with the payment provider. A detailed description of required fields should be provided by the payment provider.
     */
    public string $providerData = '{}';

    /**
     * Pass True if you require the user's full name to complete the order
     */
    public bool $needName = false;

    /**
     * Pass True if you require the user's phone number to complete the order
     */
    public bool $needPhoneNumber = false;

    /**
     * Pass True if you require the user's email address to complete the order
     */
    public bool $needEmail = false;

    /**
     * Pass True if you require the user's shipping address to complete the order
     */
    public bool $needShippingAddress = false;

    /**
     * Pass True if the user's phone number should be sent to provider
     */
    public bool $sendPhoneNumberToProvider = false;

    /**
     * Pass True if the user's email address should be sent to provider
     */
    public bool $sendEmailToProvider = false;

    /**
     * Pass True if the final price depends on the shipping method
     */
    public bool $isFlexible = false;

    /**
     * Sends the message silently. Users will receive a notification with no sound.
     */
    public bool $disableNotification = false;

    public function toArray(): array
    {
        return [
            'chat_id' => $this->chatId,
            'title' => $this->title,
            'description' => $this->description,
            'payload' => $this->payload,
            'provider_token' => $this->providerToken,
            'currency' => $this->currency,
            'prices' => $this->prices,
            'start_parameter' => $this->startParameter,
            'provider_data' => $this->providerData,
            'need_name' => $this->needName,
            'need_phone_number' => $this->needPhoneNumber,
            'need_email' => $this->needEmail,
            'need_shipping_address' => $this->needShippingAddress,
            'send_phone_number_to_provider' => $this->sendPhoneNumberToProvider,
            'send_email_to_provider' => $this->sendEmailToProvider,
            'is_flexible' => $this->isFlexible,
            'disable_notification' => $this->disableNotification,
        ];
    }
}
