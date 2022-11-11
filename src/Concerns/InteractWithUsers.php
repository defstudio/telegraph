<?php


/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */

trait InteractWithUsers
{
    public function userProfilePhotos(string $userId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_USER_PROFILE_PHOTOS;
        $telegraph->data['user_id'] = $userId;

        return $telegraph;
    }
}
