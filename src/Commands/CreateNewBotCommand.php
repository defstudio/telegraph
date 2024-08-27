<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateNewBotCommand extends Command
{
    public $signature = 'telegraph:new-bot
                            {--drop-pending-updates : drops pending updates from telegram}
                            {--max-connections=40 : maximum allowed simultaneous connections to the webhook (defaults to 40)}
                            {--secret= : secret token to be sent in a X-Telegram-Bot-Api-Secret-Token header to verify the authenticity of the webhook}
                        ';

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
            Artisan::call('telegraph:set-webhook', [
                'bot' => $bot->id,
                '--drop-pending-updates' => $this->option('drop-pending-updates'),
                '--max-connections' => $this->option('max-connections'),
                '--secret' => $this->option('secret'),
            ]);
        }

        $this->info(__('telegraph::commands.new_bot.bot_created', ['bot_name' => $bot->name]));

        return self::SUCCESS;
    }
}
