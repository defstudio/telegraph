<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\RichMessages;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Exceptions\InvoiceException;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * @phpstan-type TelegraphData array{
 *     rich_message?: array<string, mixed>
 * }
 */
class TelegraphRichMessagePayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function richMessage(string $richMessage): static
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_RICH_MESSAGE;

        /** @var TelegraphData $data */
        $data = $telegraph->data;

        $data['rich_message']['html'] = $richMessage;

        $telegraph->data = $data;

        return $telegraph;
    }

    public function asMarkdown(bool $asMarkdown = true): static
    {
        $telegraph = clone $this;

        /** @var TelegraphData $data */
        $data = $telegraph->data;

        if (!$asMarkdown) {
            if (!isset($data['rich_message']['html'])) {
                $data['rich_message']['html'] = $data['rich_message']['markdown'] ?? null;
            }

            unset($data['rich_message']['markdown']);

            return $telegraph;
        }

        $data['rich_message']['markdown'] = $data['rich_message']['html'] ?? null;

        unset($data['rich_message']['html']);

        $telegraph->data = $data;

        return $telegraph;
    }

    public function businessConnectionId(string $id): static
    {
        $telegraph = clone $this;

        $telegraph->data['business_connection_id'] = $id;

        return $telegraph;
    }

    public function messageThreadId(string $id): static
    {
        $telegraph = clone $this;

        $telegraph->data['message_thread_id'] = $id;

        return $telegraph;
    }

    public function directMessageTopicId(string $id): static
    {
        $telegraph = clone $this;

        $telegraph->data['direct_messages_topic_id'] = $id;

        return $telegraph;
    }

    public function disableNotification(bool $disableNotification = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['disable_notification'] = $disableNotification;

        return $telegraph;
    }

    public function protectContent(bool $protectContent = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['protect_content'] = $protectContent;

        return $telegraph;
    }

    public function allowPaidBroadcast(bool $allowPaidBroadcast = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['allow_paid_broadcast'] = $allowPaidBroadcast;

        return $telegraph;
    }

    public function messageEffectId(string $id): static
    {
        $telegraph = clone $this;

        $telegraph->data['message_effect_id'] = $id;

        return $telegraph;
    }

    /**
     * @param  array<string,mixed>  $parameters
     */
    public function suggestedPostParameters(array $parameters): static
    {
        $telegraph = clone $this;

        $telegraph->data['suggested_post_parameters'] = json_encode($parameters);

        return $telegraph;
    }

    protected function prepareData(): array
    {
        $data = parent::prepareData();

        if (empty($data['chat_id']) && $this->endpoint === self::ENDPOINT_SEND_RICH_MESSAGE) {
            $data['chat_id'] = $this->getChatId();
        }

        $validator = Validator::make($data, [
            'rich_message.html' => [
                'nullable',
                'string',
                'required_if:markdown,null',
                Rule::prohibitedIf(fn () => isset($data['markdown'])),
            ],

            'rich_message.markdown' => [
                'nullable',
                'string',
                'required_if:html,null',
                Rule::prohibitedIf(fn () => isset($data['html'])),
            ],

            'business_connection_id' => 'nullable|string',
            'message_thread_id' => 'nullable|string',
            'direct_messages_topic_id' => 'nullable|string',
            'disable_notification' => 'nullable|boolean',
            'protect_content' => 'nullable|boolean',
            'allow_paid_broadcast' => 'nullable|boolean',
            'message_effect_id' => 'nullable|string',
            'suggested_post_parameters' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            throw InvoiceException::validationError($validator->messages());
        }

        return $data;
    }
}
