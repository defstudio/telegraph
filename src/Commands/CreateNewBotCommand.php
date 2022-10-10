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
        $this->info(__('telegraph::commands.new_bot.starting_message'));

        $token = $this->ask(__('telegraph::commands.new_bot.enter_bot_token'));
        if (empty($token)) {
            $this->error(__('telegraph::errors.empty_token'));

            return self::FAILURE;
        }

        $name = $this->ask(__('telegraph::commands.new_bot.enter_bot_name'));

        /** @var class-string<TelegraphBot> $botModel */
        $botModel = config('telegraph.models.bot');

        /** @var TelegraphBot $bot */
        $bot = $botModel::create([
            'token' => $token,
            'name' => $name,
        ]);

        if ($this->confirm(__('telegraph::commands.new_bot.ask_to_add_a_chat'))) {
            while (empty($chat_id = $this->ask(__('telegraph::commands.new_chat.enter_chat_id')))) {
                $this->error(__('telegraph::errors.empty_chat_id'));
            }

            if ($chat_id != 'x') {
                $chat_name = $this->ask(__('telegraph::commands.new_chat.enter_chat_name'));
                $bot->chats()->create([
                    'chat_id' => $chat_id,
                    'name' => $chat_name,
                ]);
            }
        }



        if ($this->confirm(__('telegraph::commands.new_bot.ask_to_setup_webhook'))) {
            $bot->registerWebhook()->send();
        }

        $this->info(__('telegraph::commands.new_bot.bot_created', ['bot_name' => $bot->name]));

        return self::SUCCESS;
    }
}
