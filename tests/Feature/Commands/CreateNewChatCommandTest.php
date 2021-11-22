<?php

use DefStudio\Telegraph\Facades\Telegraph as Facade;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Telegraph;
use function Pest\Laravel\artisan;


it('can create a new chat', function () {
    $bot = bot();

    artisan("telegraph:new-chat")
        ->assertSuccessful();

    artisan("telegraph:new-chat $bot->id")
        ->assertSuccessful();

});
