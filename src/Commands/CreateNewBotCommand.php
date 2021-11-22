<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class CreateNewBotCommand extends Command
{
    public $signature = 'telegraph:new-bot';

    public $description = 'Set webhook url in telegram bot configuration';

    public function handle(): int
    {
        $this->info('You are about to create a new Telegram Bot');

        $token = $this->ask("Please, enter the bot token");
        if (empty($token)) {
            $this->error('Token cannot be empty');

            return self::FAILURE;
        }

        $name = $this->ask("Enter the bot name (optional)");

        /** @var TelegraphBot $bot */
        $bot = TelegraphBot::create([
            'token' => $token,
            'name' => $name,
        ]);

        if ($this->confirm("Do you want to setup a webhook for this bot?")) {
            $bot->registerWebhook()->send();
        }

        return self::SUCCESS;
    }
}
