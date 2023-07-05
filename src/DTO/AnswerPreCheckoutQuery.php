<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class AnswerPreCheckoutQuery implements Arrayable
{
    public string $preCheckoutQueryId;
    public bool $ok;
    public ?string $errorMessage;

    public function toArray(): array
    {
        $data = [
            'pre_checkout_query_id' => $this->preCheckoutQueryId,
            'ok' => $this->ok,
        ];

        if (!$this->ok && $this->errorMessage) {
            $data['error_message'] = $this->errorMessage;
        }

        return $data;
    }
}
