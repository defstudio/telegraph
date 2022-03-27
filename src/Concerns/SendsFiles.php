<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\File;

/**
 * @mixin Telegraph
 */
trait SendsFiles
{
    public function document(string $path): Telegraph
    {
        dd(File::size($path));



        $this->endpoint = self::ENDPOINT_SEND_DOCUMENT;
        $this->data['chat_id'] = $this->getChat()->chat_id;

        return $this;
    }
}
