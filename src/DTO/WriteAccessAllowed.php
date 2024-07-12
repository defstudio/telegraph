<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class WriteAccessAllowed implements Arrayable
{
    private bool $isRequest = false;
    private ?string $webApp = null;
    private bool $isAttachmentMenu = false;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     from_request: bool,
     *     web_app_name: string,
     *     from_attachment_menu: bool,
     * } $data
     */
    public static function fromArray(array $data): WriteAccessAllowed
    {
        $writeAccessAllowed = new self();

        if (isset($data['from_request'])) {
            $writeAccessAllowed->isRequest = (bool) $data['from_request'];
        }

        if (isset($data['web_app_name'])) {
            $writeAccessAllowed->webApp = $data['web_app_name'];
        }

        if (isset($data['from_attachment_menu'])) {
            $writeAccessAllowed->isAttachmentMenu = (bool) $data['from_attachment_menu'];
        }

        return $writeAccessAllowed;
    }


    public function isRequest(): bool
    {
        return $this->isRequest;
    }

    public function isWebApp(): bool
    {
        return $this->webApp !== null;
    }

    public function webAppName(): ?string
    {
        return $this->webApp;
    }

    public function isAttachmentMenu(): bool
    {
        return $this->isAttachmentMenu;
    }

    public function toArray(): array
    {
        return array_filter([
            'from_request' => $this->isRequest,
            'web_app_name' => $this->webApp,
            'from_attachment_menu' => $this->isAttachmentMenu,
        ], fn ($value) => $value !== null);
    }
}
