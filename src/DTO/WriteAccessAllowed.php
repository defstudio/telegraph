<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class WriteAccessAllowed implements Arrayable
{
    private bool $fromRequest = false;
    private ?string $webAppName = null;
    private bool $fromAttachmentMenu = false;

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
            $writeAccessAllowed->fromRequest = (bool) $data['from_request'];
        }

        if (isset($data['web_app_name'])) {
            $writeAccessAllowed->webAppName = $data['web_app_name'];
        }

        if (isset($data['from_attachment_menu'])) {
            $writeAccessAllowed->fromAttachmentMenu = (bool) $data['from_attachment_menu'];
        }

        return $writeAccessAllowed;
    }


    public function fromRequest(): bool
    {
        return $this->fromRequest;
    }

    public function fromWebApp(): bool
    {
        return $this->webAppName !== null;
    }

    public function webAppName(): ?string
    {
        return $this->webAppName;
    }

    public function fromAttachmentMenu(): bool
    {
        return $this->fromAttachmentMenu;
    }

    public function isAllowed(): bool
    {
        return $this->fromRequest() || $this->fromWebApp() || $this->fromAttachmentMenu();
    }

    public function toArray(): array
    {
        return array_filter([
            'from_request' => $this->fromRequest,
            'web_app_name' => $this->webAppName,
            'from_attachment_menu' => $this->fromAttachmentMenu,
        ], fn ($value) => $value !== null);
    }
}
