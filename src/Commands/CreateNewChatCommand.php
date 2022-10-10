<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Console\Command;

class CreateNewChatCommand extends Command
{
    public $signature = 'telegraph:new-chat
                            {bot? : the ID of the bot (if the system contain a single bot, it can be left empty)}';

    public $description = 'Create a new chat';

    public function handle(): int
    {
        /** @var int|null $bot_id */
        $bot_id = $this->argument('bot');

        /** @var class-string<TelegraphBot> $botModel */
        $botModel = config('telegraph.models.bot');

        /** @var TelegraphBot|null $bot */
        $bot = rescue(fn () => $botModel::fromId($bot_id), report: false);

        if (empty($bot)) {
            $this->error(__('telegraph::errors.missing_bot_id'));

            return self::FAILURE;
        }

        $this->info(__('telegraph::commands.new_chat.starting_message', ['bot_name' => $bot->name]));

        while (empty($chat_id = $this->ask(__('telegraph::commands.new_chat.enter_chat_id')))) {
            $this->error(__('telegraph::errors.empty_chat_id'));
        }

        if ($chat_id != 'x') {
            $chat_name = $this->ask(__('telegraph::commands.new_chat.enter_chat_name'));

            /** @var TelegraphChat $chat */
            $chat = $bot->chats()->create([
                'chat_id' => $chat_id,
                'name' => $chat_name,
            ]);

            $this->info(__('telegraph::commands.new_chat.chat_created', ['chat_name' => $chat->name, 'bot_name' => $bot->name]));
        }


        return self::SUCCESS;
    }
}
