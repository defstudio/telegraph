<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class CreateNewBotCommand extends Command
{
    public $signature = 'telegraph:new-bot';

    public $description = 'Create a new TelegraphBot';

    public function handle(): int
    {
        $this->info('You are about to create a new Telegram Bot');

        $token = $this->ask("Please, enter the bot token");
        if (empty($token)) {
            $this->error('Token cannot be empty');

            return self::FAILURE;
        }

        $name = $this->ask("Enter the bot name (optional)");

        $botModel = config('telegraph.models.bot');

        /** @var TelegraphBot|null $bot */
        $bot = $botModel::create([
            'token' => $token,
            'name' => $name,
        ]);

        if ($this->confirm("Do you want to add a chat to this bot?")) {
            while (empty($chat_id = $this->ask("Enter the chat id - press [x] to abort:"))) {
                $this->error("The chat ID cannot be null");
            }

            if ($chat_id != 'x') {
                $chat_name = $this->ask("Enter the chat name (optional):");
                $bot->chats()->create([
                    'chat_id' => $chat_id,
                    'name' => $chat_name,
                ]);
            }
        }



        if ($this->confirm("Do you want to setup a webhook for this bot?")) {
            $bot->registerWebhook()->send();
        }

        $this->info("New bot $bot->name has been created");

        return self::SUCCESS;
    }
}
