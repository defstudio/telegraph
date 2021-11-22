<?php

use function Pest\Laravel\artisan;

it('can create a new chat', function () {
    $bot = bot();

    artisan("telegraph:new-chat")
        ->assertSuccessful();

    artisan("telegraph:new-chat $bot->id")
        ->assertSuccessful();
});
