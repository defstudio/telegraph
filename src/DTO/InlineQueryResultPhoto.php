<?php /** @noinspection PhpUnused */

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Keyboard\Keyboard;

class InlineQueryResultPhoto extends InlineQueryResult
{
    protected string $type = 'photo';
    protected string $id;
    protected string $url;
    protected string $thumbUrl;
    protected int|null $width = null;
    protected int|null $height = null;
    protected string|null $title = null;
    protected string|null $caption = null;
    protected string|null $description = null;

    public static function make(string $id, string $url, string $thumbUrl): InlineQueryResultPhoto
    {
        $result = new InlineQueryResultPhoto();
        $result->id = $id;
        $result->url = $url;
        $result->thumbUrl = $thumbUrl;
        return $result;
    }

    public function width(int|null $width): InlineQueryResultPhoto
    {
        $this->width = $width;
        return $this;
    }

    public function height(int|null $height): InlineQueryResultPhoto
    {
        $this->height = $height;
        return $this;
    }

    public function title(string|null $title): InlineQueryResultPhoto
    {
        $this->title = $title;
        return $this;
    }

    public function caption(string|null $caption): InlineQueryResultPhoto
    {
        $this->caption = $caption;
        return $this;
    }

    public function description(string|null $description): InlineQueryResultPhoto
    {
        $this->description = $description;
        return $this;
    }


    public function data(): array
    {
        return [
            'id' => $this->id,
            'gif_url' => $this->url,
            'thumb_url' => $this->thumbUrl,
            'gif_width' => $this->width,
            'gif_height' => $this->height,
            'title' => $this->title,
            'caption' => $this->caption,
            'description' => $this->description,
        ];
    }
}
